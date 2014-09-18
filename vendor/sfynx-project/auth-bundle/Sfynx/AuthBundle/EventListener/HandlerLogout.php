<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Handler
 * @package    EventListerner
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-01-25
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

use Sfynx\AuthBundle\Event\ResponseEvent;
use Sfynx\AuthBundle\SfynxAuthEvents;

/**
 * Custom logout handler.
 *
 * @category   Handler
 * @package    EventListerner
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class HandlerLogout implements LogoutSuccessHandlerInterface 
{
	/**
	 * @var \Symfony\Component\Routing\Router $router
	 */
	protected $router;
		
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;    
    
    /**
     * @var \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected $redirection = '';    
    
    /**
     * @var \Symfony\Component\HttpFoundation\Request $request
     */
    protected $request;    
    
    /**
     * @var $layout
     */
    protected $layout;    
    
    /**
     * Constructs a new instance of SecurityListener.
     * 
     * @param Router $router The router
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container, Doctrine $doctrine)
    {
    	$this->router        = $container->get('sfynx.tool.route.factory');
    	$this->container     = $container;
    	$this->em            = $doctrine->getManager();
    }
    
    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request Request
     * @return RedirectResponse
     * 
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
        return $this->Redirection();  
    }    
    
    /**
     * Sets values.
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setValues()
    {
    	try {
    		// we get the best role of the user.
    		$BEST_ROLE_NAME = $this->container->get('sfynx.auth.role.factory')->getBestRoleUser();
    		if (!empty($BEST_ROLE_NAME)) {
    			$role         = $this->em->getRepository("SfynxAuthBundle:Role")->findOneBy(array('name' => $BEST_ROLE_NAME));
    			if ($role instanceof \Sfynx\AuthBundle\Entity\Role) {
    				$this->redirection = $role->getRouteLogout();
    			}
    		}    		
    	} catch (\Exception $e) {
    	}
    }    
    
    /**
     * Set logout redirection value in order to the role deconnected user
     *
     * @param FilterResponseEvent $event The event
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function Redirection()
    {
    	if (!empty($this->redirection)) {
    		$response = new RedirectResponse($this->router->getRoute($this->redirection));
    	} else {
    		$response = new RedirectResponse($this->router->getRoute('home_page'));
    	}
    	$response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('sfynx-ws-user-id', '', time() - 3600));
    	$response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('sfynx-ws-application-id', '', time() - 3600));
    	$response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('sfynx-ws-key', '', time() - 3600));
    	$response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('sfynx-layout', '', time() - 3600));
    	$response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('sfynx-screen', '', time() - 3600));
    	$response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('sfynx-redirection', '', time() - 3600));
    	$response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('_locale', '', time() - 3600));
    	// we apply all events allowed to change the redirection response
    	$event_response = new ResponseEvent($response, time() - 3600);
    	$this->container->get('event_dispatcher')->dispatch(SfynxAuthEvents::HANDLER_LOGOUT_CHANGERESPONSE, $event_response);
    	$response = $event_response->getResponse();
    	
    	return $response;
    }    
    
}