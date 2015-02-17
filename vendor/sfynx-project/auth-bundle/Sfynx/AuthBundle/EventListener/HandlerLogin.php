<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   EventListener
 * @package    Handler
 * @subpackage Authentication
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\EventListener;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

use Sfynx\AuthBundle\Event\ResponseEvent;
use Sfynx\AuthBundle\SfynxAuthEvents;


/**
 * Custom login handler.
 * This allow you to execute code right after the user succefully logs in.
 * 
 * @category   EventListener
 * @package    Handler
 * @subpackage Authentication
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
 */
class HandlerLogin
{
    /** 
     * @var \Symfony\Component\Security\Core\SecurityContext
     */
    protected $security;
    
    /**
     * @var \Symfony\Component\EventDispatcher\Event\EventDispatcher
     */
    protected $dispatcher;    

    /** 
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Symfony\Component\Security\Http\Event\InteractiveLoginEvent
     */
    protected $event;
    
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;    
    
    /**
     * @var string
     */
    protected $locale;  
    
    /**
     * Constructs a new instance of SecurityListener.
     * 
     * @param SecurityContext    $security   The security context
     * @param EventDispatcher    $dispatcher The event dispatcher
     * @param Doctrine           $doctrine   The doctrine service
     * @param ContainerInterface $container  The container service
     */
    public function __construct(SecurityContext $security, EventDispatcher $dispatcher, Doctrine $doctrine, ContainerInterface $container)
    {
        $this->security     = $security;
        $this->dispatcher   = $dispatcher;
        $this->em           = $doctrine->getManager();
        $this->container    = $container;
    }

    /**
     * Invoked after a successful login.
     * 
     * @param InteractiveLoginEvent $event The event
     * 
     * @access public
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent  $event)
    {
        // Sets event.
        $this->event    = $event;
        // Sets the user local value.
        $this->setLocaleUser();
        // Sets the state of the redirection.
        $this->setParams();
        // Associate to the dispatcher the onKernelResponse event.
        $this->dispatcher->addListener(KernelEvents::RESPONSE, array($this, 'onKernelResponse'));
        // Return the success connecion flash message.        
        $this->getFlashBag()->clear();
    }    
    
    /**
     * Sets the state of the redirection.
     *
     * @access protected
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setParams()
    {
        // we get params
        $this->date_expire    = $this->container->getParameter('sfynx.core.cookies.date_expire');
        $this->date_interval  = $this->container->getParameter('sfynx.core.cookies.date_interval');
    }    
    
    /**
     * Sets the user local value.
     *
     * @access protected
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setLocaleUser()
    {
    	if (method_exists($this->getUser()->getLangCode(), 'getId')) {
            $this->locale = $this->getUser()->getLangCode()->getId();
    	} else {
            $this->locale = $this->container->get('request')->getPreferredLanguage();
    	}
    	$this->getRequest()->setLocale($this->locale);
    }      
    
    /**
     * Invoked after the response has been created.
     * Invoked to allow the system to modify or replace the Response object after its creation.
     *
     * @param FilterResponseEvent $event The event
     * 
     * @access public
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        // we delete the username info in session if it exists
        if ($this->container->get('request')->getSession()->has('login-username')) {
            $this->container->get('request')->getSession()->remove('login-username');
        }

        // we set the dateExpire value for cokkies
        if ($this->date_expire && !empty($this->date_interval)) {
            if (is_numeric($this->date_interval)) {
                $dateExpire = time() + intVal($this->date_interval);
            } else {
                $dateExpire = new \DateTime("NOW");
                $dateExpire->add(new \DateInterval($this->date_interval));
            }
        } else {
            $dateExpire = 0;
        }        
        
        // we apply all events allowed to change the redirection response
        $event_response = new ResponseEvent(null, $dateExpire, $this->getRequest(), $this->getUser(), $this->locale);
        $this->container->get('event_dispatcher')->dispatch(SfynxAuthEvents::HANDLER_LOGIN_CHANGERESPONSE, $event_response);
        $response       = $event_response->getResponse();
        
        //
        $event->setResponse($response);        
    }    
    
    /**
     * Invoked to modify the controller that should be executed.
     *
     * @param FilterControllerEvent $event The event
     * 
     * @access public
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function onKernelController(FilterControllerEvent $event)
    {
/*         $request = $event->getRequest();
        //$controller = $event->getController();
        
        //...
        
        // the controller can be changed to any PHP callable
        $event->setController($controller); */    
    }
        
    /**
     * Invoked to allow some other return value to be converted into a Response.
     *
     * @param FilterControllerEvent $event The event
     * 
     * @access public
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        /*         $val = $event->getControllerResult();
         $response = new Response();
        // some how customize the Response from the return value
    
        $event->setResponse($response); */
    }
    
    /**
     * Invoked to allow to create and set a Response object, create and set a new Exception object, or do nothing.
     *
     * @param FilterControllerEvent $event The event
     * 
     * @access public
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        /*         $exception = $event->getException();
         $response = new Response();
        // setup the Response object based on the caught exception
        $event->setResponse($response); */
    
        // you can alternatively set a new Exception
        // $exception = new \Exception('Some special exception');
        // $event->setException($exception);
    }
    
    /**
     * Return the request object.
     *
     * @access protected
     * @return \Symfony\Component\HttpFoundation\Request
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function getRequest()
    {
        return $this->event->getRequest();
    }
    
    /**
     * Return the connected user entity object.
     *
     * @access protected
     * @return \Sfynx\AuthBundle\Entity\user
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function getUser()
    {
        return $this->event->getAuthenticationToken()->getUser();
    }
    
    /**
     * Gets the flash bag.
     *
     * @access protected
     * @return \Symfony\Component\HttpFoundation\Session\Flash\FlashBag
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function getFlashBag()
    {
        return $this->getRequest()->getSession()->getFlashBag();
    }    
    
    /**
     * Sets the welcome flash message.
     *
     * @access protected
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function setFlash()
    {
        $this->getFlashBag()->add('notice', "pi.session.flash.welcom");
    }    
}
