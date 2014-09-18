<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Handler
 * @package    EventListener
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-01-25
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Sfynx\CmfBundle\Lib\Browscap;
use Sfynx\CmfBundle\Lib\MobileDetect;

use Sfynx\AuthBundle\Event\ResponseEvent;
use Sfynx\CmfBundle\SfynxCmfEvents;

/**
 * Custom request handler.
 * Register the mobile/desktop format.
 *
 * @category   Handler
 * @package    EventListener
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class HandlerRequest
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    
    /**
     * @var \Sfynx\CmfBundle\Lib\Browscap
     */
    protected $browscap;    
    
    /**
     * @var \Sfynx\CmfBundle\Lib\MobileDetect
     */
    protected $mobiledetect;    
    
    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container        = $container; 
    }    

    /**
     * Invoked to modify the controller that should be executed.
     *
     * @param FilterControllerEvent $event The event
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
        	// ne rien faire si ce n'est pas la requête principale
        	return;
        }        
        // Set request
        $this->request = $event->getRequest($event);
        // Sets parameter template values.
        $this->setParams();
        //
        if ($this->request->cookies->has('sfynx-layout')) {
            $this->layout = $this->request->cookies->get('sfynx-layout');
        } else {
       		$this->layout = $this->container->getParameter('sfynx.auth.theme.layout.front.pc') . $this->init_pc_layout;
        }
        if ($this->request->cookies->has('sfynx-screen')) {
        	$this->screen = $this->request->cookies->get('sfynx-screen');
        } else {
            $this->screen = "layout";
        }
        if (
            !$this->request->cookies->has('sfynx-layout')
            && $this->is_browser_authorized 
            && $this->container->has("sfynx.browser.lib.mobiledetect") 
            && $this->container->has("sfynx.browser.lib.browscap")
        ) {
            // we get the browser
            \Sfynx\ToolBundle\Util\PiFileManager::mkdirr($this->browscap_cache_dir, 0777);
            if ($this->request->attributes->has('sfynx-browser')) {
                $this->browser = $this->request->attributes->get('sfynx-browser');
            } else {
                $this->browser = $this->container->get("sfynx.browser.lib.browscap")->getBrowser();
            }
            if ($this->request->attributes->has('sfynx-mobiledetect')) {
            	$this->mobiledetect = $this->request->attributes->get('sfynx-mobiledetect');
            } else {
            	$this->mobiledetect = $this->container->get("sfynx.browser.lib.mobiledetect");
            }
        	//
        	$this->request->attributes->set('sfynx-browser', $this->browser);
        	$this->request->attributes->set('sfynx-mobiledetect', $this->mobiledetect);
        	//
        	if ($this->browser->isMobileDevice) {
        		if (!$this->mobiledetect->isTablet()) {
        			$this->screen = "layout-poor";
        		} elseif ($this->mobiledetect->isTablet()) {
        			$this->screen = "layout-medium";
        		} else {
        			$this->screen = 'layout-medium';
        		}
        		$this->layout = $this->container->getParameter('sfynx.auth.theme.layout.front.mobile') . $this->init_mobile_layout.'\\' . $this->screen . '.html.twig';
        		$this->request->setRequestFormat('mobile');
        	}
        }        
        // we add sfynx-layout and sfynx-screen info in the request
        $this->request->attributes->set('sfynx-layout', $this->layout);
        $this->request->attributes->set('sfynx-screen', $this->screen);
    }     
    
    /**
     * Sets parameter template values.
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setParams()
    {
    	$this->init_pc_layout         = $this->container->getParameter('sfynx.auth.layout.init.pc.template');
    	$this->init_mobile_layout     = $this->container->getParameter('sfynx.auth.layout.init.mobile.template');
    	$this->is_browser_authorized  = $this->container->getParameter("sfynx.auth.browser.switch_layout_mobile_authorized");
    	//    	
    	if ($this->container->has("sfynx.browser.lib.mobiledetect") && $this->container->hasParameter("sfynx.browser.browscap.cache_dir")) {
    	    $this->browscap_cache_dir  = $this->container->getParameter("sfynx.browser.browscap.cache_dir");
    	} else {
    	    $this->browscap_cache_dir  = null;
    	}
    }   

}