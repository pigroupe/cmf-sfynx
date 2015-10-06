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

use Symfony\Component\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Sfynx\AuthBundle\Event\ViewObject\ResponseEvent;
use Sfynx\AuthBundle\Event\SfynxAuthEvents;
use Sfynx\AuthBundle\Entity\Role;

/**
 * Custom logout handler.
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
class HandlerLogout implements LogoutSuccessHandlerInterface 
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    /**
     * @var Router
     */
    protected $router;
		
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * @var EntityManager
     */
    protected $em;    
    
    /**
     * @var SecurityContext
     */
    protected $security;      
    
    /**
     * @var EventDispatcher
     */
    protected $dispatcher;     
    
    /**
     * @var RedirectResponse
     */
    protected $redirection = '';    
    
    /**
     * @var Request
     */
    protected $request;    
    
    /**
     * @var string
     */
    protected $layout;    
    
    /**
     * Constructs a new instance of SecurityListener.
     * 
     * @param ContainerInterface $container The container service
     * @param Doctrine           $doctrine  The doctrine service
     * @param SecurityContext    $security   The security context
     * @param EventDispatcher    $dispatcher The event dispatcher
     */
    public function __construct(LoggerInterface $logger, ContainerInterface $container, Doctrine $doctrine, SecurityContext $securityContext, EventDispatcher $dispatcher)
    {
        $this->logger     = $logger;
    	$this->router     = $container->get('sfynx.tool.route.factory');
    	$this->container  = $container;
    	$this->em         = $doctrine->getManager();
        $this->security   = $securityContext;
        $this->dispatcher = $dispatcher;
    }
    
    /**
     * Invoked after a successful logout.
     * 
     * @param Request $request The request service
     * 
     * @access public
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function onLogoutSuccess(Request $request)
    {
    	//print_r('priority 3-bis');
    	// set request
    	$this->request = $request;
        // Sets init.
        $this->setValues();   
        // set redirection
        return $this->redirection();  
    }    
    
    /**
     * Sets values.
     *
     * @access protected
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setValues()
    {
    	try {
            // we get the best role of the user.
            $BEST_ROLE_NAME = $this->container
                    ->get('sfynx.auth.role.factory')
                    ->getBestRoleUser();
            
            if (!empty($BEST_ROLE_NAME)) {
                $role = $this->em
                        ->getRepository("SfynxAuthBundle:Role")
                        ->findOneBy(array('name' => $BEST_ROLE_NAME));
                if ($role instanceof Role) {
                    $this->redirection = $role->getRouteLogout();
                }
            }    		
    	} catch (\Exception $e) {
    	}
    }    
    
    /**
     * Set logout redirection value in order to the role deconnected user
     *
     * @access protected
     * @return RedirectResponse
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function redirection()
    {
    	if (!empty($this->redirection)) {
            $response = new RedirectResponse($this->router->getRoute($this->redirection), 302);
    	} else {
            $response = new RedirectResponse($this->router->getRoute('home_page'), 302);
    	}
        
    	$response->headers->setCookie(new Cookie('sfynx-ws-user-id', '', time() - 3600));
    	$response->headers->setCookie(new Cookie('sfynx-ws-application-id', '', time() - 3600));
    	$response->headers->setCookie(new Cookie('sfynx-ws-key', '', time() - 3600));
    	$response->headers->setCookie(new Cookie('sfynx-layout', '', time() - 3600));
    	$response->headers->setCookie(new Cookie('sfynx-screen', '', time() - 3600));
    	$response->headers->setCookie(new Cookie('sfynx-redirection', '', time() - 3600));
    	$response->headers->setCookie(new Cookie('_locale', '', time() - 3600));
        
    	// we apply all events allowed to change the redirection response
    	$event_response = new ResponseEvent($response, time() - 3600);
    	$this->dispatcher->dispatch(SfynxAuthEvents::HANDLER_LOGOUT_CHANGERESPONSE, $event_response);
    	$response = $event_response->getResponse();
        
        // Set log
        $this->logger->info("User ".$this->getUser()." has been saved", array('user' => $this->getUser()));        
    	
    	return $response;
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
        if ($this->isUsernamePasswordToken()) {
            return $this->security->getToken()->getUser();
        } else {
            //return $this->security->getToken()->getUser();
            return 'UserPhpUnit';
        }
    }    
    
    /**
     * Return if yes or no the user is UsernamePassword token.
     *
     * @return boolean
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isUsernamePasswordToken()
    {
        if ($this->security->getToken() instanceof \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken) {
            return true;
        } else {
            return false;
        }
    }        
}
