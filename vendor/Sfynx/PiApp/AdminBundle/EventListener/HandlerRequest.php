<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   Admin_Eventlistener
 * @package    EventListener
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-01-25
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use PiApp\AdminBundle\Lib\Browscap;
use PiApp\AdminBundle\Lib\MobileDetect;

/**
 * Custom request handler.
 * Register the mobile/desktop format.
 *
 * @category   Admin_Eventlistener
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
     * @var \PiApp\AdminBundle\Lib\Browscap
     */
    protected $browscap;    
    
    /**
     * @var \PiApp\AdminBundle\Lib\MobileDetect
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
        //if (!$this->request->hasPreviousSession()) {
        //    return;
        //}        
    	//print_r('priority 2');
        // set request
    	$this->request = $event->getRequest($event);
        $locale        = $this->request->getLocale();
        // Sets parameter template values.
        $this->setParams();
        // home page redirection
        $isPrefixLocale = $this->isPrefixLocale();
        if ($isPrefixLocale instanceof Response) {
            $event->setResponse($isPrefixLocale);
        	return;
        }        
        // SEO redirecrtion for old url
        $SEOUrl = $this->isSEOUrl();
        if ($SEOUrl instanceof Response) {
        	$event->setResponse($SEOUrl);
        	return;
        }       
        // Test if we are or not in the scop.
        $isNoScope = $this->isNoScope();
        if ($isNoScope instanceof Response) {
            $event->setResponse($isNoScope);
            return;
        }
        // we add sfynx-layout info in the request
        if ($this->request->cookies->has('sfynx-layout')) {
            $this->layout =  $this->request->cookies->get('sfynx-layout');
        } else {
        	if (isset($browser->isMobileDevice) && $browser->isMobileDevice) {
        		if ($this->request->attributes->has('sfynx-screen')) {
        		    $WurflScreen = $this->request->attributes->get('sfynx-screen');
        		} else {
        		    $WurflScreen = 'layout-medium';
        		}
        		$this->layout        = 'PiAppTemplateBundle::Template\\Layout\\Mobile\\'.$this->init_mobile_layout.'\\' . $WurflScreen . '.html.twig';
        	} else {
        		$this->layout        = 'PiAppTemplateBundle::Template\\Layout\\Pc\\'.$this->init_pc_layout;
        	}
        }
        $this->request->attributes->set('sfynx-layout', $this->layout);
        $this->request->attributes->set('sfynx-screen', "layout"); 
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
    	$this->date_expire                              = $this->container->getParameter('pi_app_admin.cookies.date_expire');
    	$this->date_interval                            = $this->container->getParameter('pi_app_admin.cookies.date_interval');
    
    	$this->init_pc_layout                           = $this->container->getParameter('pi_app_admin.layout.init.pc.template');
    	$this->init_pc_redirection                         = $this->container->getParameter('pi_app_admin.layout.init.pc.redirection');
    	$this->init_mobile_layout                       = $this->container->getParameter('pi_app_admin.layout.init.mobile.template');
    	$this->init_mobile_redirection                     = $this->container->getParameter('pi_app_admin.layout.init.mobile.redirection');
    
    	$this->is_switch_redirection_seo_authorized     = $this->container->getParameter('pi_app_admin.page.seo_redirection.seo_authorized');
    	$this->seo_redirection_repository     			= $this->container->getParameter('pi_app_admin.page.seo_redirection.seo_repository');
    	$this->seo_redirection_file_name			    = $this->container->getParameter('pi_app_admin.page.seo_redirection.seo_file_name');
    	if (empty($this->seo_redirection_repository)) {
    		$this->seo_redirection_repository = $this->container->getParameter("kernel.root_dir") . "/cache/seo";
    	}
    	if (empty($this->seo_redirection_file_name)) {
    		$this->seo_redirection_file_name = "seo_links.yml";
    	}
    	    	
    	$this->is_prefix_locale                         = $this->container->getParameter("pi_app_admin.page.route.with_prefix_locale");
    	
    	$this->is_scop_authorized                       = $this->container->getParameter("pi_app_admin.page.scop.authorized");
    	$this->scop_globals                             = $this->container->getParameter("pi_app_admin.page.scop.globals");
    	$this->scop_browscap_cache_dir                  = $this->container->getParameter("pi_app_admin.page.scop.browscap.cache_dir");
    }   

    /**
     * Sets the good home_page
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isPrefixLocale()
    {
        if ($this->is_prefix_locale) {
        	$route = $this->container->get('request')->get('route_name');
        	$url   = $this->container->get('request')->getRequestUri();
        	if (($route != 'home_page') && ($url == '/')) {
        		$url_homepage = $this->container->get('bootstrap.RouteTranslator.factory')->getRoute('home_page');
        		$response     = new RedirectResponse($url_homepage, 301);
        		
        		return $response;
        	}
        }
        
        return false;
    }    
    
    /**
     * Sets the SEO url valule if is a old url.
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isSEOUrl()
    {
        if ( $this->is_switch_redirection_seo_authorized ) {
            $dossier  = $this->seo_redirection_repository . "/old_urls/";
            \PiApp\AdminBundle\Util\PiFileManager::mkdirr($dossier, 0777);
            $fileSeo  = $this->seo_redirection_repository . "/" . $this->seo_redirection_file_name;
            
        	//$is_cache_not_created = \PiApp\AdminBundle\Util\PiFileManager::isEmptyDir($dossier); // very fast
            //if (file_exists($fileSeo) && $is_cache_not_created) {
            
            //$path_tmp_file = $dossier.'tmp.file';
            //if (file_exists($fileSeo) && !file_exists($path_tmp_file)) {
            	// we set the tmp file
            	//$result = \PiApp\AdminBundle\Util\PiFileManager::save($path_tmp_file, "", 0777, LOCK_EX);

            // if all cache seo files are not created from the seo file, we create them.
            $all_cache_files = \PiApp\AdminBundle\Util\PiFileManager::GlobFiles($dossier . '*.cache' ); // more fast in linux but not in windows
            if ( file_exists($fileSeo) && (count($all_cache_files) == 0) ) {
        		$this->container->get("pi_filecache")->getClient()->setPath($dossier);
        		$file_handle = fopen($fileSeo, "r");
        		while (!feof($file_handle)) {
        			$line = (string) fgets($file_handle);
        			$line_infos = explode(':', $line);
        			if (
        				isset( $line_infos[0]) && !empty( $line_infos[0])
        				&&
        				isset( $line_infos[1]) && !empty( $line_infos[1])
        			) {
        				$this->container->get("pi_filecache")->set(str_replace(PHP_EOL, '', $line_infos[0]), str_replace(PHP_EOL, '', $line_infos[1]), 0);
        			}
        		}
        		fclose($file_handle);
        	}
        	//
	    	$filename = $this->request->getPathInfo();
	        $this->container->get("pi_filecache")->getClient()->setPath($dossier);
	        if (!$this->container->get("pi_filecache")->get($filename)) {
	        	$SEOUrl =  false;
	        } else {
	        	$SEOUrl = $this->container->get("pi_filecache")->get($filename);
	        }
    	} else {
    		$SEOUrl = false;
    	}
    	if ($SEOUrl) {
    		// we set response
    		$response = new RedirectResponse($SEOUrl, 301);
    		
    		return $response;
    		//$response->setResponse(new Response(\PiApp\AdminBundle\Util\PiFileManager::getCurl($SEOUrl, null, null, $this->request->getUriForPath(''))));
    	}
    	
    	return false;
    }  
    
    /**
     * Sets the SEO url valule if is a old url.
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isNoScope()
    {
        if ( $this->is_scop_authorized ) {
            // we set libs.
            \PiApp\AdminBundle\Util\PiFileManager::mkdirr($this->scop_browscap_cache_dir, 0777);
            $this->mobiledetect = $this->container->get("pi_app_admin.lib.mobiledetect");
            $this->browscap     = $this->container->get("pi_app_admin.lib.browscap");
            // we set the browser  and globals information
            $browser = $this->browscap->getBrowser();
            $globals = $this->scop_globals;
            // we add browser info in the request
            $this->request->attributes->set('sfynx-browser', $browser);
            $this->request->attributes->set('sfynx-mobiledetect', $this->mobiledetect);
            // we stop the website content if the navigator is not configurate correctly.
            $nav_desktop = strtolower($browser->Browser);
            $nav_mobile  = strtolower($browser->Platform);
            $isNoScope   = false;
            if (
                (!$browser->isMobileDevice)
                &&
                (!isset($globals["navigator"][$nav_desktop]) || floatval($browser->Version)  <= $globals["navigator"][$nav_desktop])
            ) {
            	$isNoScope = true;
            } elseif (
            	($browser->isMobileDevice && !$this->mobiledetect->isTablet())
            	&&
            	(!isset($globals["mobile"][$nav_mobile]) || floatval($browser->Platform_Version)  <= $globals["mobile"][$nav_mobile] )
            ) {
            	$isNoScope = true;
            } elseif (
            	($browser->isMobileDevice && $this->mobiledetect->isTablet())
            	&&
            	(!isset($globals["tablet"][$nav_mobile]) || floatval($browser->Platform_Version)  <= $globals["tablet"][$nav_mobile] )
            ) {
            	$isNoScope = true;
            }
            if ( ($browser->Version == 0.0) || ($browser->Platform_Version == 0.0) ) {
            	$isNoScope = false;
            }
            if($isNoScope) {
                if (!$browser->isMobileDevice) {
                	if ( isset($globals["navigator"][$nav_desktop]) && (floatval($browser->Version)  <= $globals["navigator"][$nav_desktop]) ) $isNav = false; else $isNav = true;
                } elseif ($bc->getBrowser()->isMobileDevice) {
                	if ( isset($globals["navigator"][$nav_mobile]) && (floatval($browser->Platform_Version)  <= $globals["navigator"][$nav_mobile]) ) $isNav = false; else $isNav = true;
                }
                $isCookies    = $browser->Cookies;
                $isJs         = $browser->JavaScript;
                // we set response
                $response     = new \Symfony\Component\HttpFoundation\Response($this->request->getUri());
                $response->headers->set('Content-Type', 'text/html');
                $response     = $this->container->get('templating')->renderResponse('PiAppTemplateBundle:Template\\Nonav:nonav.html.twig', array('locale' => $locale, 'isCookies'=>$isCookies, 'isJs'=>$isJs, 'isNav'=>$isNav), $response);
                
                return $response;
            }
        }

        return false;
    }    
      
}