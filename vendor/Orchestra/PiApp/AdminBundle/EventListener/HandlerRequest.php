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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

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
    public function __construct(ContainerInterface $container, Browscap $Browscap, MobileDetect $mobiledetect)
    {
        $this->container        = $container;
        $this->mobiledetect     = $mobiledetect;
        $this->browscap         = $Browscap;
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
    	//print_r('priority 2');
        // set request
    	$this->request    = $event->getRequest($event);
        $locale      = $this->request->getLocale();
        $globals     = $this->container->get("twig")->getGlobals();
        // Sets parameter template values.
        $this->setParams();
        // SEO for old url
        $SEOUrl = $this->isSEOUrl();
        if ($SEOUrl) {
        	// we set response
        	$event->setResponse(new Response(\PiApp\AdminBundle\Util\PiFileManager::getCurl($SEOUrl, null, null, $this->request->getUriForPath(''))));
        } else {        
            // we set the browser information
            $browser = $this->browscap->getBrowser();
            // we add browser info in the request
            $this->request->attributes->set('orchestra-browser', $browser);
            $this->request->attributes->set('orchestra-mobiledetect', $this->mobiledetect);        
            // we stop the website content if the navigator is not configurate correctly.
            $nav_desktop    = strtolower($browser->Browser);
            $nav_mobile        = strtolower($browser->Platform);
            $isNoScope = false;
            if ( 
                (!$browser->isMobileDevice) 
                &&
                (!isset($globals["navigator"][$nav_desktop]) || floatval($browser->Version)  <= $globals["navigator"][$nav_desktop]) 
            ){
                $isNoScope = true;
            }elseif ( 
                ($browser->isMobileDevice && !$this->mobiledetect->isTablet())
                &&  
                (!isset($globals["mobile"][$nav_mobile]) || floatval($browser->Platform_Version)  <= $globals["mobile"][$nav_mobile] )
            ){
                $isNoScope = true;
            }elseif ( 
                ($browser->isMobileDevice && $this->mobiledetect->isTablet())
                &&  
                (!isset($globals["tablet"][$nav_mobile]) || floatval($browser->Platform_Version)  <= $globals["tablet"][$nav_mobile] )
            ){
                $isNoScope = true;
            }
            if ( ($browser->Version == 0.0) || ($browser->Platform_Version == 0.0) ) {
                $isNoScope = false;
            }
            if ($isNoScope){
                if (!$browser->isMobileDevice) {
                    if ( isset($globals["navigator"][$nav_desktop]) && (floatval($browser->Version)  <= $globals["navigator"][$nav_desktop]) ) $isNav = false; else $isNav = true;
                } elseif ($bc->getBrowser()->isMobileDevice) {
                    if ( isset($globals["navigator"][$nav_mobile]) && (floatval($browser->Platform_Version)  <= $globals["navigator"][$nav_mobile]) ) $isNav = false; else $isNav = true;
                }
                $isCookies     = $browser->Cookies;
                $isJs         = $browser->JavaScript;
                // we set response
                $response     = new \Symfony\Component\HttpFoundation\Response($this->request->getUri());
                $response->headers->set('Content-Type', 'text/html');
                $response     = $this->container->get('templating')->renderResponse('PiAppTemplateBundle:Template\\Nonav:nonav.html.twig', array('locale' => $locale, 'isCookies'=>$isCookies, 'isJs'=>$isJs, 'isNav'=>$isNav), $response);
                $event->setResponse($response);
            } else {
            	// Sets the user local value.
            	$this->setLocaleNavigator();
                // we add orchestra-layout info in the request
                if ($this->request->cookies->has('orchestra-layout')){
                    $this->layout =  $this->request->cookies->get('orchestra-layout');
                } else {
                	if (isset($browser->isMobileDevice) && $browser->isMobileDevice){
                		if ($this->request->attributes->has('orchestra-screen'))    $WurflScreen = $this->request->attributes->get('orchestra-screen'); else    $WurflScreen = 'layout-medium';
                		$this->layout        = 'PiAppTemplateBundle::Template\\Layout\\Mobile\\'.$this->init_mobile_layout.'\\' . $WurflScreen . '.html.twig';
                	} else {
                		$this->layout        = 'PiAppTemplateBundle::Template\\Layout\\Pc\\'.$this->init_pc_layout;
                	}
                }
                $this->request->attributes->set('orchestra-layout', $this->layout);
                $this->request->attributes->set('orchestra-screen', "layout"); 
            }
        }
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
    	$this->init_mobile_redirection                  = $this->container->getParameter('pi_app_admin.layout.init.mobile.redirection');
    
    	$this->is_switch_redirection_seo_authorized     = $this->container->getParameter('pi_app_admin.page.seo_redirection.seo_authorized');
    	$this->seo_redirection_repository     			= $this->container->getParameter('pi_app_admin.page.seo_redirection.seo_repository');
    	$this->seo_redirection_file_name			    = $this->container->getParameter('pi_app_admin.page.seo_redirection.seo_file_name');
    	if (empty($this->seo_redirection_repository)) {
    		$this->seo_redirection_repository = $this->container->getParameter("kernel.root_dir") . "/cache/seo";
    	}
    	if (empty($this->seo_redirection_file_name)) {
    		$this->seo_redirection_file_name = "seo_links.yml";
    	}
    	    	
    	$this->is_switch_language_browser_authorized    = $this->container->getParameter('pi_app_admin.page.switch_language_browser_authorized');
    	$this->is_init_redirection_authorized           = $this->container->getParameter('pi_app_admin.page.switch_layout_init_redirection_authorized');
    }    
    


    /**
     * Sets the user local value.
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setLocaleNavigator()
    {
    	if ($this->is_switch_language_browser_authorized) {
    		$lang_value = $this->container->get('pi_app_admin.locale_manager')->parseDefaultLanguage();
    		$this->request->setLocale($lang_value);
    	}
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
        $dossier  = $this->seo_redirection_repository . "/old_urls/";
   		$fileSeo  = $this->seo_redirection_repository . "/" . $this->seo_redirection_file_name;
        if (
    	    $this->is_switch_redirection_seo_authorized
    	    &&
    	    \PiApp\AdminBundle\Util\PiFileManager::mkdirr($dossier, 0777)
    	) {
        	// if all cache seo files are not created from the seo file, we create them.
        	$all_cache_files = \PiApp\AdminBundle\Util\PiFileManager::GlobFiles($dossier . '*.cache' );
        	if (file_exists($fileSeo) && is_array($all_cache_files) && (count($all_cache_files) === 0)) {
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
	        if (!$this->container->get("pi_filecache")->get($filename)){
	        	return false;
	        } else {
	        	return $this->container->get("pi_filecache")->get($filename);
	        }
    	} else {
    		return false;
    	}
    }   

}