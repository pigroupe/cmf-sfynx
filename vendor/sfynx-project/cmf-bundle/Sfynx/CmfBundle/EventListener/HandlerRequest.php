<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @category   EventListener
 * @package    Handler
 * @subpackage Request
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
namespace Sfynx\CmfBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sfynx\AuthBundle\Event\ViewObject\ResponseEvent;
use Sfynx\CmfBundle\Event\SfynxCmfEvents;

/**
 * Custom request handler.
 * Register the mobile/desktop format.
 *
 * @category   EventListener
 * @package    Handler
 * @subpackage Request
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt
 * @since      2014-07-18
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
     * @param GetResponseEvent $event The event
     * 
     * @return null
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
    	// Set the heritage.json file if does not exist
    	if (!$this->container->get('sfynx.auth.role.factory')->isJsonFileExisted()) {
            $this->container->get('sfynx.auth.role.factory')->setJsonFileRoles();
    	}
        // Set request
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
    }     
    
    /**
     * Sets parameter template values.
     *
     * @access protected
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setParams()
    {
    	$this->date_expire                          = $this->container->getParameter('sfynx.core.cookies.date_expire');
    	$this->date_interval                        = $this->container->getParameter('sfynx.core.cookies.date_interval');
    	$this->is_switch_redirection_seo_authorized = $this->container->getParameter('pi_app_admin.seo.redirection.authorized');
    	$this->seo_redirection_repository           = $this->container->getParameter('pi_app_admin.seo.redirection.repository');
    	$this->seo_redirection_file_name            = $this->container->getParameter('pi_app_admin.seo.redirection.file_name');
    	$this->is_prefix_locale                     = $this->container->getParameter("pi_app_admin.page.route.with_prefix_locale");
    	$this->is_scop_authorized                   = $this->container->getParameter("pi_app_admin.page.scop.authorized");
    	$this->scop_globals                         = $this->container->getParameter("pi_app_admin.page.scop.globals");
        //
    	if ($this->container->has("sfynx.browser.lib.mobiledetect") 
                && $this->container->hasParameter("sfynx.browser.browscap.cache_dir")
        ) {
            $this->browscap_cache_dir  = $this->container->getParameter("sfynx.browser.browscap.cache_dir");
    	} else {
            $this->browscap_cache_dir  = null;
    	}
    }   

    /**
     * Sets the good home_page
     *
     * @access protected
     * @return false|RedirectResponse
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isPrefixLocale()
    {
        if ($this->is_prefix_locale) {
            $route = $this->container->get('request')->get('route_name');
            $url   = $this->container->get('request')->getRequestUri();
            if (($route != 'home_page') && ($url == '/')) {
                $url_homepage = $this->container
                        ->get('sfynx.tool.route.factory')
                        ->getRoute('home_page');
                $response     = new RedirectResponse($url_homepage, 301);
                // we apply all events allowed to change the redirection response
                $event_response = new ResponseEvent($response, $this->date_expire);
                $this->container
                    ->get('event_dispatcher')
                    ->dispatch(
                        SfynxCmfEvents::HANDLER_REQUEST_CHANGERESPONSE_PREFIX_LOCALE_REDIRECTION,
                        $event_response
                    );
                $response = $event_response->getResponse();

                return $response;
            }
        }
        
        return false;
    }    
    
    /**
     * Sets the SEO url valule if is a old url.
     *
     * @access protected
     * @return false|RedirectResponse
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isSEOUrl()
    {
        if ( $this->is_switch_redirection_seo_authorized ) {
            $dossier  = $this->seo_redirection_repository . "/old_urls/";
            \Sfynx\ToolBundle\Util\PiFileManager::mkdirr($dossier, 0777);
            $fileSeo  = $this->seo_redirection_repository . "/" . $this->seo_redirection_file_name;
            
            //$is_cache_not_created = \Sfynx\ToolBundle\Util\PiFileManager::isEmptyDir($dossier); // very fast
            //if (file_exists($fileSeo) && $is_cache_not_created) {
            
            //$path_tmp_file = $dossier.'tmp.file';
            //if (file_exists($fileSeo) && !file_exists($path_tmp_file)) {
            // we set the tmp file
            //$result = \Sfynx\ToolBundle\Util\PiFileManager::save($path_tmp_file, "", 0777, LOCK_EX);

            // if all cache seo files are not created from the seo file, we create them.
            $all_cache_files = \Sfynx\ToolBundle\Util\PiFileManager::GlobFiles($dossier . '*.cache' ); // more fast in linux but not in windows
            if ( file_exists($fileSeo) && (count($all_cache_files) == 0) ) {
                $this->container->get("sfynx.cache.filecache")->getClient()->setPath($dossier);
                $file_handle = fopen($fileSeo, "r");
                while (!feof($file_handle)) {
                    $line = (string) fgets($file_handle);
                    $line_infos = explode(':', $line);
                    if (
                        isset( $line_infos[0]) && !empty( $line_infos[0])
                        &&
                        isset( $line_infos[1]) && !empty( $line_infos[1])
                    ) {
                        $this->container->get("sfynx.cache.filecache")->set(str_replace(PHP_EOL, '', $line_infos[0]), str_replace(PHP_EOL, '', $line_infos[1]), 0);
                    }
                }
                fclose($file_handle);
            }
            //
            $filename = $this->request->getPathInfo();
            $this->container->get("sfynx.cache.filecache")->getClient()->setPath($dossier);
            if (!$this->container->get("sfynx.cache.filecache")->get($filename)) {
                $SEOUrl =  false;
            } else {
                $SEOUrl = $this->container->get("sfynx.cache.filecache")->get($filename);
            }
    	} else {
            $SEOUrl = false;
    	}
    	if ($SEOUrl) {
            // we set response
            $response = new RedirectResponse($SEOUrl, 301);
            // we apply all events allowed to change the redirection response
            $event_response = new ResponseEvent($response, $this->date_expire);
            $this->container
                ->get('event_dispatcher')
                ->dispatch(
                    SfynxCmfEvents::HANDLER_REQUEST_CHANGERESPONSE_SEO_REDIRECTION, 
                    $event_response
                );
            $response = $event_response->getResponse();

            return $response;
            //$response->setResponse(new Response(\Sfynx\ToolBundle\Util\PiFileManager::getCurl($SEOUrl, null, null, $this->request->getUriForPath(''))));
    	}
    	
    	return false;
    }  
    
    /**
     * Sets the SEO url valule if is a old url.
     *
     * @access protected
     * @return false|RedirectResponse
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isNoScope()
    {
        $locale   = $this->request->getLocale();
        if (
            $this->is_scop_authorized 
            && 
            $this->container->has("sfynx.browser.lib.mobiledetect") 
            && 
            $this->container->has("sfynx.browser.lib.browscap")
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
            // we set globals information
            $globals     = $this->scop_globals;
            $nav_desktop = strtolower($this->browser->Browser);
            $nav_mobile  = strtolower($this->browser->Platform);
            $isNoScope   = false;
            if (
                (!$this->browser->isMobileDevice)
                &&
                (!isset($globals["navigator"][$nav_desktop]) || floatval($this->browser->Version)  <= $globals["navigator"][$nav_desktop])
            ) {
            	$isNoScope = true;
            } elseif (
            	($this->browser->isMobileDevice && !$this->mobiledetect->isTablet())
            	&&
            	(!isset($globals["mobile"][$nav_mobile]) || floatval($this->browser->Platform_Version)  <= $globals["mobile"][$nav_mobile] )
            ) {
            	$isNoScope = true;
            } elseif (
            	($this->browser->isMobileDevice && $this->mobiledetect->isTablet())
            	&&
            	(!isset($globals["tablet"][$nav_mobile]) || floatval($this->browser->Platform_Version)  <= $globals["tablet"][$nav_mobile] )
            ) {
            	$isNoScope = true;
            }
            if ( ($this->browser->Version == 0.0) || ($this->browser->Platform_Version == 0.0) ) {
            	$isNoScope = false;
            }
            if($isNoScope) {
                if (!$this->browser->isMobileDevice) {
                	if ( isset($globals["navigator"][$nav_desktop]) && (floatval($this->browser->Version)  <= $globals["navigator"][$nav_desktop]) ) $isNav = false; else $isNav = true;
                } elseif ($bc->getBrowser()->isMobileDevice) {
                	if ( isset($globals["navigator"][$nav_mobile]) && (floatval($this->browser->Platform_Version)  <= $globals["navigator"][$nav_mobile]) ) $isNav = false; else $isNav = true;
                }
                $isCookies    = $this->browser->Cookies;
                $isJs         = $this->browser->JavaScript;
                // we set response
                $response     = new \Symfony\Component\HttpFoundation\Response($this->request->getUri());
                $response->headers->set('Content-Type', 'text/html');
                $response     = $this->container->get('templating')->renderResponse('SfynxTemplateBundle:Template\\Nonav:nonav.html.twig', array('locale' => $locale, 'isCookies'=>$isCookies, 'isJs'=>$isJs, 'isNav'=>$isNav), $response);
                // we apply all events allowed to change the response
                $event_response = new ResponseEvent($response, $this->date_expire);
                $this->container->get('event_dispatcher')->dispatch(SfynxCmfEvents::HANDLER_REQUEST_CHANGERESPONSE_NOSCOPE, $event_response);
                $response = $event_response->getResponse();
                
                return $response;
            }
        }

        return false;
    }          
}
