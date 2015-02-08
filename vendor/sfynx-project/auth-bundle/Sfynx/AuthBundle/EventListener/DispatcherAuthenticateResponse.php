<?php
/**
 * This file is part of the <Auth> project.
 *
 * @subpackage Dispatcher
 * @package    Event
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-02-06
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sfynx\AuthBundle\Event\ResponseEvent;
use Sfynx\AuthBundle\Event\RedirectionEvent;
use Sfynx\AuthBundle\Entity\Role;
use Sfynx\AuthBundle\Entity\Layout;
use Sfynx\ToolBundle\Route\RouteTranslatorFactory;
use Sfynx\AuthBundle\SfynxAuthEvents;
use Doctrine\ORM\EntityManager;

/**
 * Response handler of authenticate response
 *
 * @subpackage Dispatcher
 * @package    Event
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class DispatcherAuthenticateResponse
{
   /**
    * @var ContainerInterface $container
    */
    protected $container; 
    
    /** 
     * @var EntityManager $em
     */
    protected $em;    
    
    /**
     * @var RouteTranslatorFactory $router
     */
    protected $router;    
    
    /**
     * @var boolean
     */    
    public $is_browser_authorized;
    
    /**
     * @var string
     */    
    public $screen;
    
    /**
     * @var string $redirect route name of the login redirection
     */    
    public $redirect = "";
    
    /**
     * @var string $template layout file name
     */    
    public $template = "";
    
    /**
     * @var string
     */
    public $layout;    
    

   /**
    * Constructor.
    *
    * @param ContainerInterface $container
    */   
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;  
        $this->em        = $this->container->get('doctrine')->getManager();
        $this->router    = $this->container->get('sfynx.tool.route.factory');
    }

   /**
    * Invoked to modify the controller that should be executed.
    *
    * @param ResponseEvent $event The event
    * 
    * @access protected
    * @return void
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */   
    public function onPiAuthenticateResponse(ResponseEvent $event)
    {
        $this->setParams();
        $this->setResponse($event);
        $this->setCookies($event);
        $this->setRequest($event);
    }  
    
    /**
     * Sets parameters
     *
     * @access protected
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setParams()
    {
        // we get vars
        $this->layout         = $this->container->get('request')->attributes->get('sfynx-layout');
        $this->screen         = $this->container->get('request')->attributes->get('sfynx-screen');        
        // we get the best role of the user.
        $this->BEST_ROLE_NAME = $this->container->get('sfynx.auth.role.factory')->getBestRoleUser();
        if (!empty($this->BEST_ROLE_NAME)) {
            $role         = $this->em->getRepository("SfynxAuthBundle:Role")->findOneBy(array('name' => $this->BEST_ROLE_NAME));
            if ($role instanceof Role) {
                $RouteLogin = $role->getRouteLogin();
                if (!empty($RouteLogin) && !is_null($RouteLogin)) {
                    $this->redirect = $RouteLogin;
                }
                if ($role->getLayout() instanceof Layout) {
                    $FilePc = $role->getLayout()->getFilePc();
                    if (!empty($FilePc)  && !is_null($FilePc)) {
                        $this->template = $FilePc;
                    }
                }
            }
        }
        // Sets layout
        if ($this->is_browser_authorized
            && $this->container->get('request')->attributes->has('sfynx-browser') 
            && $this->container->get('request')->attributes->get('sfynx-browser')->isMobileDevice
        ) {
            $this->layout    = $this->container->getParameter('sfynx.auth.theme.layout.admin.mobile') . $this->screen . '.html.twig';
        } else {
            $this->layout    = $this->container->getParameter('sfynx.auth.theme.layout.admin.pc').$this->template;
        }
    }       
   
    /**
     * Sets response
     *
     * @access protected
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function setResponse(ResponseEvent $event) 
    {
        // we apply all events allowed to change the url redirection
        $event_redirection = new RedirectionEvent($this->router, $this->redirect);
        $this->container->get('event_dispatcher')->dispatch(SfynxAuthEvents::HANDLER_LOGIN_CHANGEREDIRECTION, $event_redirection);
        $response = $event_redirection->getResponse();  
        
        // we deal with the case where the connection is limited to a set of roles (ajax or not ajax connection).
        if (isset($_POST['roles']) && !empty($_POST['roles'])) {
            $all_authorization_roles = json_decode($_POST['roles'], true);
            // If the permisssion is not given.
            if (is_array($all_authorization_roles) && !in_array($this->BEST_ROLE_NAME, $all_authorization_roles)) {
                if ($event->getRequest()->isXmlHttpRequest()) {
                    $response = new Response(json_encode("no-authorization"));
                    $response->headers->set('Content-Type', 'application/json');
                } else {
                    $referer_url = $this->container->get('sfynx.tool.route.factory')->getRefererRoute();
                    $response = new RedirectResponse($referer_url);
                }
            } else {
                if ($event->getRequest()->isXmlHttpRequest()) {
                    $response = new Response(json_encode("ok"));
                    $response->headers->set('Content-Type', 'application/json');
                }
            }
        // we deal with the case where the connection is done in ajax without limited connection.
        } elseif ($event->getRequest()->isXmlHttpRequest()) {
            $response = new Response(json_encode("ok"));
            $response->headers->set('Content-Type', 'application/json');
        } else {
            $response = $event->getResponse();
        }

        $event->setResponse($response);
    }
    
    /**
     * Sets cookies
     *
     * @access protected
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function setCookies(ResponseEvent $event) 
    {
        $locale     = $event->getLocale();
        $dateExpire = $event->getDateExpire();
        $response   = $event->getResponse();
        //
        $response->headers->setCookie(new Cookie('sfynx-layout', $this->layout, $dateExpire));
        $response->headers->setCookie(new Cookie('sfynx-screen', $this->screen, $dateExpire));
        $response->headers->setCookie(new Cookie('sfynx-redirection', $this->redirect, $dateExpire));
        $response->headers->setCookie(new Cookie('sfynx-framework', 'Symfony 2.3', $dateExpire));
        $response->headers->setCookie(new Cookie('_locale', $locale, $dateExpire));     
        //
        $event->setResponse($response);
    }
    
    /**
     * Sets request
     *
     * @access protected
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function setRequest(ResponseEvent $event) 
    {
        $request = $event->getRequest();
        $request->attributes->set('sfynx-layout', $this->layout);
        //
        $event->setRequest($request);        
    }
}
