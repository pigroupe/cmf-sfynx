<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage Admin_Managers
 * @package    Manager
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-01-23
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response as Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Psr\Log\LoggerInterface;

use Sfynx\CmfBundle\Builder\PiPageManagerBuilderInterface;
use Sfynx\CmfBundle\Repository\TranslationPageRepository;
use Sfynx\CmfBundle\Manager\PiCoreManager;
use Sfynx\CmfBundle\Entity\Page;
use Sfynx\CmfBundle\Entity\TranslationPage;
use Sfynx\CmfBundle\Entity\Block;
use Sfynx\CmfBundle\Entity\Widget;

/**
 * Description of the Page manager
 *
 * @subpackage Admin_Managers
 * @package    Manager
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiPageManager extends PiCoreManager implements PiPageManagerBuilderInterface 
{    
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    /**
     * @var \Sfynx\CmfBundle\Manager\PiWidgetManager
     */    
    protected $widgetManager;    
    
    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(LoggerInterface $logger, ContainerInterface $container)
    {
        $this->logger = $logger;
        parent::__construct($container);
    }
    
    /**
     * Returns the render of the current page.
     * 
     * @param string  $lang      The locale value
     * @param boolean $isSetPage True to force the setting page
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2012-01-23
     */
    public function render($lang = '', $isSetPage = false)
    {
        // we disable the handler
        $em = $this->container->get('doctrine')->getManager();
        $eventManager = $em->getEventManager();
        $eventManager->removeEventListener(
            array('kernel.request'),
            $this->container->get('sfynx.auth.locale_handler')
        );
        $eventManager->removeEventListener(
            array('kernel.request'),
            $this->container->get('sfynx.auth.request_handler')
        );
        $eventManager->removeEventListener(
            array('kernel.request'),
            $this->container->get('pi_app_admin.request_handler')
        );
        // we set the langue
        if (empty($lang)) {
            $lang = $this->language;
        }
        // Initialize page
        if ($this->getCurrentPage() instanceof Page) {
            // we get the current page.
            $page = $this->getCurrentPage();
            // we set the page.
            if ($isSetPage) {
                $this->setPage($page);
            }
        } else {
            if ($this->isAnonymousToken()) {
                // We inform that the page does not exist fi the user is connected.
                $this->setFlash("pi.session.flash.page.notexist", 'notice');
            }
            // we redirect to the public url home page.
            return $this->redirectHomePublicPage();
        }
        //
        $id_page = $page->getId();
        $isEnabled_page = $page->getEnabled();
        // if the page is enabled.
        if ($page && $isEnabled_page) {
            $url_    = $this->container->get('request')->getRequestUri();
            // Initialize response
            $response = $this->getResponseByIdAndType('page', $id_page);            
            // we register only the translation page asked in the $lang value.
            $this->setTranslations($page, false);
            // we get the translation of the current page in terms of the lang value.
            $pageTrans	= $this->getTranslationByPageId($id_page, $lang);
            // If the translation page is secure and the user is not connected, we return to the home page.
            if ($pageTrans 
                    && $pageTrans->getSecure() 
                    && $this->isAnonymousToken()
            ) {
                return $this->redirectHomePublicPage();
            }    
            // If the translation page is not authorized to publish, we return to the home page.
            if ($pageTrans 
                    && ($pageTrans->getStatus() != TranslationPageRepository::STATUS_PUBLISH) 
                    && $this->isAnonymousToken()
            ) {
                return $this->redirectHomePublicPage();
            }        
            // If the translation page is secure and the user is not authorized, we return to the home page.
            if ($pageTrans 
                    && $pageTrans->getSecure() 
                    && $this->isUsernamePasswordToken()
            ) {
                // Gets all user roles.
                $user_roles            = $this->container->
                        get('sfynx.auth.role.factory')
                        ->getAllUserRoles();
                // Gets the best role authorized to access to the entity.
                $authorized_page_roles = $this->container
                        ->get('sfynx.auth.role.factory')
                        ->getBestRoles($pageTrans->getHeritage());                
                $right = false;
                if (is_null($authorized_page_roles)) {
                    $right = true;
                } else {
                    foreach ($authorized_page_roles as $key => $role_page) {
                        if (in_array($role_page, $user_roles))
                            $right = true;
                    }
                }
                if (!$right) {
                    return $this->redirectHomePublicPage();
                }
            }    
            // Handle 404
            // We don't show the page if :
            // * The page doesn't have a translation set.
            // * the translation doesn't have a published status.
            if (!$pageTrans) {
                // we register all translations page linked to one page.
                $this->setTranslations($page, $lang);
                // we get the translation of the current page in another language if it exists.
                $pageTrans	= $this->getTranslationByPageId($id_page, $lang);
                if (!$pageTrans) {
                    $page	= $this->setPageByRoute('error_404', true);
                    if (!$page) {
                        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("We haven't set in the data fixtures the error page message in the $lang locale !");
                    }
                    $response->setStatusCode(404);
                }
            }
            // we register the Etag value in the json file if does not exist.
            $this->setJsonFileEtag('page', $id_page, $lang, array('page-url'=>$url_));
            // Create a Response with a Last-Modified header.
            $response = $this->configureCache($page, $response);
            // Check that the Response is not modified for the given Request.
            if ($response->isNotModified($this->container->get('request'))){
                // We set the reponse
                $this->setResponsePage($page, $response);
                // return the 304 Response immediately
                return $response;
            } else {
                // or render a template with the $response you've already started
                // $response->setContent($this->container->get('twig')->render($this->renderSource($id, $lang_), array()));
                $response = $this->container
                        ->get('pi_app_admin.caching')
                        ->renderResponse($this->Etag, array(), $response);
                
                return $response;
            }
        } else {
            return $this->redirectHomePublicPage();
        }
    }
    
    /**
     * Returns the render source of one page.
     *
     * @param string $id     id value
     * @param string $lang   lang value
     * @param array  $params params value
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2012-01-31
     */
    public function renderSource($id, $lang = '', $params = null)
    {
    	// we set the page.
        if (!$this->getPageById($id)) {
            $this->setPageById($id);
        }
        // we set the langue
        if (empty($lang))    $lang = $this->language;
        // we init params
        $init_pc_layout        = str_replace("/", "\\\\", $this->getPageById($id)->getLayout()->getFilePc());
        $init_pc_layout        = str_replace("\\", "\\\\", $init_pc_layout);
        $init_mobile_layout    = str_replace("\\", "\\\\", $this->getPageById($id)->getLayout()->getFileMobile());
        if (empty($init_pc_layout)) {
            $init_pc_layout    = $this->container->getParameter('sfynx.auth.layout.init.pc.template');
        }
        if (empty($init_mobile_layout)) {
            $init_mobile_layout = $this->container->getParameter('sfynx.auth.layout.init.mobile.template');
        }
        // we get the translation of the current page in terms of the lang value.
        $pageTrans       = $this->getTranslationByPageId($id, $lang);    //if ($lang == 'fr') print_r($pageTrans->getLangCode()->getId());
        if ($pageTrans instanceof TranslationPage){
            $description = $pageTrans->getMetaDescription();
            $keywords    = $pageTrans->getMetaKeywords();
            $title       = $pageTrans->getMetaTitle();
        } else {
            $description = "";
            $keywords    = "";        
            $title       = "";
        }
        // we return a 404 error if the meta title is a 404 type
        $meta_title = $this->container->get('pi_app_admin.twig.extension.seo')->getTitlePageFunction($lang, $title);
        if ($meta_title == '_error_404_') {
        	throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('The page does not exist');
        }   
        //
        $meta_page = $this->container->get('pi_app_admin.twig.extension.seo')->getMetaPageFunction($lang, array(
                'description' => $description,
                'keywords'    => $keywords,
                'title'       => $meta_title
        ));
        // we get the css file of the page.
        $stylesheet = $this->getPageById($id)->getPageCss();
        // we get the js file of the page.
        $javascript  = $this->getPageById($id)->getPageJs();
        // we create the source page.
        $source  = "{% set layout_screen = app.request.attributes.get('sfynx-screen') %}\n";
        $source .= "{% set is_switch_layout_mobile_authorized = getParameter('sfynx.auth.browser.switch_layout_mobile_authorized') %}\n";
        $source .= "{% set is_esi_disable_after_post_request = getParameter('pi_app_admin.page.esi.disable_after_post_request') %}\n";
        $source .= "{% set is_widget_ajax_disable_after_post_request = getParameter('pi_app_admin.page.widget.ajax_disable_after_post_request') %}\n";
        $source .= "{% set app_request_request_count = app.request.request.count() %}\n";
        $source .= "{% if layout_screen is empty or not is_switch_layout_mobile_authorized  %}\n";
        $source .= "{%     set layout_screen = 'layout' %}\n";
        $source .= "{% endif %}\n";
        $source .= "{% if layout_screen in ['layout-poor', 'layout-medium', 'layout-high', 'layout-ultra'] %}\n";
        $source .= "{%     set layout_nav = getParameter('sfynx.auth.theme.layout.front.mobile')~'".$init_mobile_layout."\\\'~ layout_screen ~'.html.twig' %}\n";
        $source .= "{% else %}\n";
        $source .= "{%     set layout_nav = getParameter('sfynx.auth.theme.layout.front.pc')~'".$init_pc_layout."' %}\n";
        $source .= "{% endif %}\n";
        $source .= "{% extends layout_nav %}\n";        
        // we set stylesheets
        if ($stylesheet instanceof \Doctrine\ORM\PersistentCollection) {
            foreach($stylesheet as $s){
                $source     .= "{% stylesheet '".$s->getUrl()."' %} \n";
            }
        }
        // we set javascripts
        if ($javascript instanceof \Doctrine\ORM\PersistentCollection){
            foreach($javascript as $s){
                $source     .= "{% javascript '".$s->getUrl()."' %} \n";
            }
        }
        //$source     .= "{% set meta_title = title_page(app.request.locale,'{$title}') %} \n";
        $source     .= "{% block global_title %}";
        $source     .= "{{ parent() }} \n";
        $source     .= "{{ \"{$meta_title}\"|striptags }} \n";
        $source     .= "{% endblock %} \n";
        $source     .= "{% set global_local_language = '".$this->language."' %} \n";
        $source     .= " \n";
        $source     .= "{% block global_meta %} \n";
        $source     .= "    {$meta_page}";
        //$source     .= "    {{ metas_page(app.request.locale, {'description':\"{$description}\",'keywords':\"{$keywords}\",'title':\"{$meta_title}\"})|raw }} \n";
        $source     .= "{{ parent() }}    \n";
        $source     .= "{% endblock %} \n";
        // we set all widgets of all blocks
        if (isset($this->blocks[$id]) && !empty($this->blocks[$id])) {
            $all_blocks = $this->blocks[$id];
            foreach ($all_blocks as $block) {
                // if the block is not disabled.                
                if ($block->getEnabled()) {
                    $source     .= "{% block ".$block->getName()." %} \n";
                    $source     .= "{{ parent() }}    \n";
                    $source     .= "<sfynx id=\"block__".$block->getId()."\" data-id=\"".$block->getId()."\" data-name=\"".$this->container->get('translator')->trans($block->getName())."\" style=\"display:block\"> \n";
                    // we set all widget of the block
                    if (isset($this->widgets[$id][$block->getId()]) && !empty($this->widgets[$id][$block->getId()])){
                        $all_widgets      = $this->widgets[$id][$block->getId()];
                        $widget_position = array();
                        foreach ($all_widgets as $widget) {
                            if ($widget->getEnabled()) {
                                if (isset($this->widgets[$id][$block->getId()][$widget->getId()]) && !empty($this->widgets[$id][$block->getId()][$widget->getId()])){
                                    // we get the widget manager
                                    $widgetManager      = $this->getWidgetManager();
                                    // we set the result
                                    $widgetManager->setCurrentWidget($this->widgets[$id][$block->getId()][$widget->getId()]);
                                    // we initialize js and css script of the widget
                                    $widgetManager->setScript();
                                    // we initialize init of the widget
                                    $widgetManager->setInit();                
                                    if ($widget->getPosition() && ($widget->getPosition() != 0)){
                                        $pos = $widget->getPosition();
                                        // we return the render (cache or not)
                                        $widget_position[ $pos ]  = "<sfynx id=\"widget__".$widget->getId()."\" data-id=\"".$widget->getId()."\" style=\"display:block\"> \n";
                                        $widget_position[ $pos ] .= $widgetManager->render($this->language). " \n";
                                        $widget_position[ $pos ] .= "</sfynx> \n";
                                    } else {
                                        // we return the render (cache or not)
                                        $widget_position[]        = "<sfynx id=\"widget__".$widget->getId()."\" data-id=\"".$widget->getId()."\" > \n";
                                        $widget_position[]       .= $widgetManager->render($this->language) . " \n";
                                        $widget_position[]       .= "</sfynx> \n";
                                    } 
                                    // we set the js and css scripts.
                                    $container  = strtoupper($widget->getPlugin());
                                    $this->script['js']   = array_merge($this->script['js'], $widgetManager->getScript('js', 'array'));
                                    $this->script['css']  = array_merge($this->script['css'], $widgetManager->getScript('css', 'array'));
                                    $this->script['init'] = array_merge($this->script['init'], $widgetManager->getScript('init', 'array'));
                                }
                            }
                        }
                        ksort($widget_position);
                        $source        .= implode(" \n", $widget_position);
                    }
                    $source     .= " </sfynx> \n";
                    $source     .= " {% endblock %} \n";
                }
            }            
        }
        // we set the js script of the widget
        $source     .= "{% block global_script_js %} \n";
        $source        .= " {{ parent() }} \n"; 
        $source     .= " <script type=\"text/javascript\"> \n";
        $source     .= " //<![CDATA[ \n";
        $source     .= $this->getScript('js', 'implode') . " \n";
        $source     .= " //]]> \n";
        $source     .= " </script> \n";
        $source     .= "{% endblock %} \n";
        // we set the css script of the widget
        $source     .= "{% block global_script_css %} \n";
        $source        .= " {{ parent() }} \n";
        $source     .= " <style type=\"txt/css\"> \n<!-- \n";
        $source     .= $this->getScript('css', 'implode') . " \n";
        $source     .= " \n--> \n</style> \n";
        $source     .= "{% endblock %} \n";
        // we set the widget script of the ajax render
        $is_render_service_with_ajax = $this->container->getParameter('pi_app_admin.page.widget.render_service_with_ajax');
        if($is_render_service_with_ajax) {
            $source     .= "{% block global_script_divers_footer %} \n";
            $source     .= " {{ parent() }} \n";
            $source     .= "{{ obfuscateLinkJS('ajax','hiddenLinkWidget')|raw }}\n";
            $source     .= "{% endblock %} \n";
        }        
        // we set all initWidget
        $source        = $this->getScript('init', 'implode') . "\n" . $source;
        
//         print_r($source);
//         print_r("<br /><br /><br />");
//         exit;
        
        return $source;
    }
    
    /**
     * Returns the render ESI source of a widget.
     *
     * @param string $serviceName serviceName value
     * @param string $method      method value
     * @param string $id          id value
     * @param string $lang        lang value
     * @param array  $params      params value
     * @param array  $options     options value
     * @param mixed  $response    a response instance
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2012-01-31
     */
    public function renderESISource($serviceName, $method, $id, $lang = '', $params = null, $options = null, Response $response = null)
    {
        // we set the langue
        if (empty($lang))    $lang = $this->language;
            // we initialize
            $this->initializeRequest($lang, $options);
            // we set the result widget
            $result = $this->container->get($serviceName)->$method($id, $lang, $params);
            // set response
            if (is_null($response)) {
                    $response = new Response($result);
            } else {
                    $response->setContent($result);
            }
            // Allows proxies to cache the same content for different visitors.
            if (isset($options['public']) && $options['public']) {
                    $response->setPublic();
            } 
            if (isset($options['lifetime']) && $options['lifetime']) {
                    $response->setSharedMaxAge($options['lifetime']);
                    $response->setMaxAge($options['lifetime']);
            }
            // Returns a 304 "not modified" status, when the template has not changed since last visit.
            if (
                isset($options['cacheable']) && $options['cacheable']
                &&
                isset($options['update']) && $options['update']
            ) {
                    $response->setLastModified(new \DateTime(date('Y-m-d H:i:s', $options['update'])));
            } else {
                    $response->setLastModified(new \DateTime());
            }
            //
            $is_force_private_response           = $this->container->getParameter("pi_app_admin.page.esi.force_private_response_for_all");
            $is_force_private_response_with_auth = $this->container->getParameter("pi_app_admin.page.esi.force_private_response_only_with_authentication");
            if ( 
                $is_force_private_response
                ||
                ($this->isUsernamePasswordToken() && $is_force_private_response_with_auth)		
            ) {
                    $response->headers->set('Pragma', "no-cache");
                    $response->headers->set('Cache-control', "private");
            } elseif ( isset($options['lifetime']) && ($options['lifetime'] == 0) ) {
                    $response->setSharedMaxAge(0);
                    $response->setMaxAge(0);
            }

            return $response;
    }	

    /**
     * Initialize the request with a new uri.
     *
     * @param $options    ['REQUEST_URI', 'REDIRECT_URL']
     * @return void
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-02-16
     */
    public function initializeRequest($lang = '', array $options = array())
    {
        // we set the langue
        if (empty($lang))    $lang = $this->language;
            // we duplicate the current request
            $clone_request = $this->container->get('request')->duplicate();
            // we modify the header request
            if (isset($options['REQUEST_URI']) && !empty($options['REQUEST_URI'])) {
                    $clone_request->server->set('REQUEST_URI', $options['REQUEST_URI']);
                    $_SERVER['REQUEST_URI'] = $options['REQUEST_URI'];
            }
            if (isset($options['REDIRECT_URL']) && !empty($options['REDIRECT_URL'])) {
                    $clone_request->server->set('REDIRECT_URL', $options['REDIRECT_URL']);
                    $_SERVER['REDIRECT_URL'] = $options['REDIRECT_URL'];
            }
            // we initialize the request with new values.
            $query      = $clone_request->query->all();
            $request    = $clone_request->request->all();
            $attributes = $clone_request->attributes->all();
            $cookies    = $clone_request->cookies->all();
            $files      = $clone_request->files->all();
            $server     = $clone_request->server->all();
            $this->container->get('request')->initialize($query, $request, $attributes, $cookies, $files, $server);
            // we get the _route value of the new uri
            $match = $this->container->get('sfynx.tool.route.factory')->getLocaleRoute($lang, array('result'=>'match') );
// 		if ($match && is_array($match)) {
// 			foreach($match as $k => $v) {
// 				$_GET[$k] = $v;
// 				$this->container->get('request')->query->set($k, $v);
// 				$this->container->get('request')->attributes->set($k, $v);
// 			}
// 		}
//      $request = Request::createFromGlobals();  =>  $request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
            // we set the _route value
            $this->container->get('request')->query->set('_route', $match['_route']);
            $this->container->get('request')->attributes->set('_route', $match['_route']);
            $this->container->get('request')->attributes->set('_locale', $lang);
            $this->container->get('request')->setLocale($lang);
            $_GET['_locale'] = $lang;
    } 
    
    /**
     * Sets and return a page by id.
     *
     * @param integer $idPage Id of a page entity
     *
     * @return void
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-16
     */
    public function setPageById($idPage)
    {
        $page = $this->getRepository('Page')->find($idPage);        
        if ($page instanceof Page) {
            // we set the result
            $this->setCurrentPage($page);
            // we set the page.
            $this->setPage($page);
            // we return the setting page.
            return $page;            
        } else {
            return false;
        }
    }    
    
    /**
     * Sets and return a page by url and slug.
     *
     * @param string  $url       url value of a page
     * @param string  $slug      slug value of a translation of a page
     * @param boolean $isSetPage True to set all information of a page in the container
     * 
     * @return Page
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-23
     */
    public function setPageByParams($url, $slug, $isSetPage = false) 
    {
        // Get corresponding page
        if (!$slug && !$url) {
            $page = $this->getRepository('page')->getHomepage();
        } else {
            $slug = explode('/', $slug);
            $slug = $slug[count($slug) - 1];
            $page = $this->getRepository('page')->getPageByUrlAndSlug($url, $slug);
        }        
        if ($page instanceof Page) {
            // we set the result
            $this->setCurrentPage($page);
            // we set the page.
            if ($isSetPage) {
                $this->setPage($page);
            }
            // we return the setting page.
            return $page;
        } else {
            return false;
        }
    }

    /**
     * Sets and return a page by a route name.
     *
     * @param string  $route     Route page value
     * @param boolean $isSetPage True to force the setting page
     *
     * @return false|Page
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-23
     */
    public function setPageByRoute($route = '', $isSetPage = false)
    {
        // Get corresponding page
        if (!$route || empty($route)) {
            $page = $this->getRepository('page')->getHomepage();
        } else {
            $page = $this->getPageByRoute($route, false);
        }
        if ($page instanceof Page) {
            // we set the result
            $this->setCurrentPage($page);
            // we set the page.
            if ($isSetPage) {
                $this->setPage($page);
            }
            // we return the setting page.
            return $page;
        } else {
            return false;
        }
    }  

    /**
     * Redirect to the url by his route name.
     *
     * @param string $route_name Route name of a page
     * 
     * @return string content page
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2012-06-11
     */
    public function redirectPage($route_name = 'error_404')
    {
        $url_redirection = $this->container->get('sfynx.tool.route.factory')->getRoute($route_name);
        header('Location: '. $url_redirection);
        exit;
    }  	

    /**
     * Sets a page and construct all it information.
     *
     * @param Page $page A page entity
     *
     * @return void
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-31
     */
    public function setPage(Page $page)
    {
        $id = $page->getId();
        if (!$this->getPageById($id)){
            // we register all translations page linked to one page.
            $this->setTranslations($page);
            // we register all blocks linked to one page.
            $this->setBlocks($page);    
            // we register all widgets linked to one page
            $this->setWidgets($page);        
            // we register the page
            $this->pages[$id] = $page;
        }
    }    
    
    /**
     * Sets all the related translations linked to one page.
     *
     * @param Page         $page   Page entity
     * @param false|string $locale THe locale value
     *
     * @return void
     * @access private
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-23
     */
    private function setTranslations(Page $page, $locale = false)
    {
        if (!isset($this->translations[$page->getId()]) || empty($this->translations[$page->getId()])) {            
            if (!$locale) {
                // records all translations
                $all_translations = $page->getTranslations();
                if (count($all_translations) >= 1) {
                    foreach ($all_translations as $translation) {
                        $this->translations[$page->getId()][$translation->getLangCode()->getId()] = $translation;
                    }
                }
            } else {
                $translationPage = $this->getRepository('translationPage')->findOneBy(array('page' => $page->getId(), 'langCode'=>$locale));
                if ($translationPage instanceof \Sfynx\CmfBundle\Entity\TranslationPage) {
                    $this->translations[$page->getId()][$locale] = $translationPage;
                }
            }
        }
    }    
    
    /**
     * Sets all the related block linked to one page.
     *
     * @param Page $page Page entity
     * 
     * @return void
     * @access private
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-23
     */
    private function setBlocks(Page $page)
    {
        if (!isset($this->blocks[$page->getId()]) || empty($this->blocks[$page->getId()])) {
            $all_blocks = $page->getBlocks();
            // records all blocks
            foreach ($all_blocks as $block) {
                $this->blocks[$page->getId()][$block->getId()] = $block;
            }
        }
    }
    
    /**
     * Sets all the related block linked to one page.
     *
     * @param Page $page Page entity
     *
     * @return void
     * @access private
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-31
     */
    private function setWidgets(Page $page)
    {
        if (isset($this->blocks[$page->getId()]) && !empty($this->blocks[$page->getId()])) {
            $all_blocks = $this->blocks[$page->getId()];
            // records all widgets
            foreach ($all_blocks as $block) {
                $all_widgets = $block->getWidgets();
                foreach ($all_widgets as $widget) {
                    $this->widgets[$page->getId()][$block->getId()][$widget->getId()] = $widget;
                }            
            }
        }
    }    
    
    /**
     * Sets the response to one page.
     * 
     * @param Page     $page     Page entity
     * @param Response $response Response instance
     *
     * @return void
     * @access private
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-01-31
     */
    private function setResponsePage(Page $page, Response $response)
    {
        $this->responses['page'][$page->getId()] = $response;
    }    
    
    /**
     * Sets the Widget manager service.
     *
     * @return void
     * @access private
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-31
     */
    private function setWidgetManager()
    {
        $this->widgetManager = $this->container->get('pi_app_admin.manager.widget');
    }
    
    /**
     * Gets the Widget manager service
     *
     * @return PiWidgetManager
     * @access private
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-31
     */
    private function getWidgetManager()
    {
        if (empty($this->widgetManager)) {
            $this->setWidgetManager();
        }
    
        return $this->widgetManager;
    }    
    
    /**
     * It redirects to the public url home page.
     *
     * @return void
     * @access private
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-14
     */
    private function redirectHomePublicPage(){
        // It tries to redirect to the original page.
        // probleme avec les esi => pas de valeur retourné
        $url = $this->container->get('request')->headers->get('referer');
        if (empty($url)) {
            $url = $this->container->get('router')->generate('home_page');
        }
        
        return new RedirectResponse($url);
    }    
    
    /**
     * Return the ChildrenHierarchy result of the rubrique entity.
     *
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-29
     */
    public function getChildrenHierarchyRub()
    {
        $_em = $this->container->get('pi_app_admin.repository');    
        $options = array(
                'decorate' => true,
                'rootOpen' => "\n <ul> \n",
                'rootClose' => "\n </ul> \n",
                'childOpen' => "    <li class='collapsed' > \n",        // 'childOpen' => "    <li class='collapsed' > \n",
                'childClose' => "    </li> \n",
                'nodeDecorator' => function($node) {
                    return  '<a data-rub="'.$node['id'].'" >'.$node["titre"].'</a><p class="pi_tree_desc">'.$node["descriptif"]."</p>";
                }
        );
        $htmlTree = $_em->getRepository('Rubrique')->childrenHierarchy(
                null, /* starting from root nodes */
                false, /* load all children, not only direct */
                $options
        );
    
        return $htmlTree;
    }
    
    /**
     * Modify the tree result with the pages blocks.
     * 
     * @param string $htmlTree Content value
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-29
     */
    public function setTreeWithPages($htmlTree)
    {
        if (empty($htmlTree)) {
            return $htmlTree;
        }    
        $htmlTree = $this->container->get('sfynx.tool.string_manager')->trimUltime($htmlTree);
        if (preg_match_all('#<a data-rub="(?P<id_rubrique>(.*))" >(?P<titre>(.*))</a><p class="pi_tree_desc">(?P<descriptif>(.*))</p>#sU', $htmlTree, $matches_rubs)){
            foreach ($matches_rubs['id_rubrique'] as $key => $idRubrique) {
                $result_simple   = preg_split('#<a data-rub="'.$idRubrique.'" >(.*)</a><p class="pi_tree_desc">(.*)</p>#sU', $htmlTree);
                $result_multiple = preg_split('#<a data-rub="'.$idRubrique.'" >(.*)</a><p class="pi_tree_desc">(.*)</p>(.*)<ul>#sU', $htmlTree);    
                if (count($result_simple) == 2) {
                    $allRubriquePages = $this->getPagesByRub($idRubrique);
                    if (!empty($allRubriquePages))
                        $htmlTree = $result_simple[0]
                        . '<a data-rub="'.$idRubrique.'" >'.$matches_rubs['titre'][$key].'</a><p class="pi_tree_desc">'.$matches_rubs['descriptif'][$key].'</p>'
                        . '<ul>'
                        . $allRubriquePages
                        . '</ul>'
                        . $result_simple[1];
                }
                if (count($result_multiple) == 2) {
                    $allRubriquePages = $this->getPagesByRub($idRubrique);
                    if (!empty($allRubriquePages))
                        $htmlTree = $result_multiple[0]
                        . '<a data-rub="'.$idRubrique.'" >'.$matches_rubs['titre'][$key].'</a><p class="pi_tree_desc">'.$matches_rubs['descriptif'][$key].'</p>'
                        . '<ul>'
                        . $allRubriquePages
                        . $result_multiple[1];
                }
            }
        }
    
        return $htmlTree;
    }
    
    /**
     * Sets the home page.
     * 
     * @param string $htmlTree Content value
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-29
     */
    public function setHomePage($htmlTree)
    {
        $pages_content    = "";
        $page             =  $this->container->get('pi_app_admin.repository')->getRepository('Page')->getHomePage();    
        if ($page instanceof Page){
            if ( !$page->getTranslations()->isEmpty() ) {
                $locales = array();
                $pages_content .= "<li><p>Home Page ".$page->getId()."</p><a href='#'>url : ".$page->getUrl()."</a><p></p><ul>";
                foreach ($page->getTranslations() as $key=>$translationPage) {
                    if ($translationPage instanceof TranslationPage){
                        $local = $translationPage->getLangCode()->getId();
                        try {
                            $route = $this->container->get('router')->generate( $page->getRouteName(), array('locale' => $local) );
                        } catch (\Exception $e) {
                            $route = $this->container->get('router')->generate( $page->getRouteName());
                        }
                        $pages_content .= "<li>";
                        $pages_content .= "<p>local ".$local."</p><a href='".$route."'>slug : ".$translationPage->getSlug()."</a><p class='pi_tree_title'>".$translationPage->getTitre()."</p><p class='pi_tree_desc'>".$translationPage->getDescriptif ()."</p>";
                        $pages_content .= "</li>";
                    }
                }
                $pages_content .= "</ul></li>";
            }
        }
        $pages_content = preg_replace_callback(
            '#<ul>#sU', 
            function($matches) use ($pages_content) {
                return '<ul>'.$pages_content;
            },
            $htmlTree,
            1
        );
        
        return $pages_content;
    }
    
    /**
     * Gets all page of a rubrique.
     * 
     * @param integer $idRubrique Id rubrique
     * 
     * @return string
     * @access private
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-28
     */
    private function getPagesByRub($idRubrique)
    {
        $pages_content   = "";
        $pagesByRubrique =  $this->container
                ->get('pi_app_admin.repository')
                ->getRepository('Page')
                ->getAllPageByRubriqueId($idRubrique)
                ->getQuery()
                ->getResult();
        if (is_array($pagesByRubrique)) {
            foreach($pagesByRubrique as $key => $page) {
                if ($page instanceof Page) {
                    if ( !$page->getTranslations()->isEmpty() ) {
                        $locales = array();
                        $pages_content .= "<li><p>Page ".$page->getId()."</p><a href='#'>url : ".$page->getUrl()."</a><p></p><ul>";
                        foreach ($page->getTranslations() as $key=>$translationPage) {
                            if ($translationPage instanceof TranslationPage) {
                                $local = $translationPage->getLangCode()->getId();
                                try {
                                    $route = $this->container->get('router')->generate( $page->getRouteName(), array('locale' => $local) );
                                } catch (\Exception $e) {
                                    try {
                                        $route = $this->container->get('router')->generate( $page->getRouteName() );
                                    } catch (\Exception $e) {
                                        $route = "";
                                    }
                                }
                                $pages_content .= "<li class='css-transform-rotate dhtmlgoodies_sheet.gif' >";
                                $pages_content .= "<p>local ".$local."</p><a href='".$route."'>slug : ".$translationPage->getSlug()."</a><p class='pi_tree_title'>".$translationPage->getTitre()."</p><p class='pi_tree_desc'>".$translationPage->getDescriptif ()."</p>";
                                $pages_content .= "</li>";
                            }
                        } // end foreach
                        $pages_content .= "</ul></li>";
                    }
                }
            } // end foreach
        }
    
        return $pages_content;
    }    
    
    /**
     * Add node numeber in the <li>.
     * 
     * @param string $htmlTree Content value
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-29
     */
    public function setNode($htmlTree)
    {
        if (empty($htmlTree)) {
            return $htmlTree;
        }
        //print_r($htmlTree);
        $htmlTree           = $this->container->get('sfynx.tool.string_manager')->trimUltime($htmlTree);
        $matches_balise_rub = preg_split('#<li>(?P<num>(.*))#sU', $htmlTree);
        $max_key            = 1;
        if ($matches_balise_rub) {
            //print_r($matches_balise_il);
            $htmlTree = '';
            $max_key = count($matches_balise_rub)-1;
            foreach ($matches_balise_rub as $key => $value) {
                if ($max_key != $key) {
                    $htmlTree .= $value . '<li id="node'.($key+1).'">';
                } else {
                    $htmlTree .= $value;
                }
            }
        }
        $matches_balise_page     = preg_split("#<li class='dhtmlgoodies_sheet.gif'>(?P<num>(.*))#sU", $htmlTree);
        $max_key                = 1;
        if ($matches_balise_page) {
            //print_r($matches_balise_il);
            $htmlTree = '';
            $max_key = count($matches_balise_page)-1;
            foreach($matches_balise_page as $key => $value) {
                if ($max_key != $key) {
                    $htmlTree .= $value . '<li id="node'.($key+1).'" class=\'dhtmlgoodies_sheet.gif\'>';
                } else {
                    $htmlTree .= $value;
                }
            }
        }        
        //print_r($htmlTree);exit;
    
        return $htmlTree;
    }

    /**
     * Refresh the cache of the tree Chart page.
     *
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-05-11
     */
    public function cacheTreeChartPageRefresh()
    {
        $em = $this->container->get('doctrine')->getManager();
        // we manage the "tree chart page"
        $params_treechart = array();
        $params_treechart['action']  = "renderByClick";
        $params_treechart['id']      = ".org-chart-page";
        $params_treechart['menu']    = "page";
        // we sort an array by key in reverse order
        krsort($params_treechart);
        // we create de Etag cache
        $params_treechart = json_encode($params_treechart);
        $params_treechart = str_replace(':', '#', $params_treechart);
        // we refresh all caches 
        $all_lang    = $em->getRepository('SfynxAuthBundle:Langue')->findByEnabled(true);
        foreach($all_lang as $key => $lang) {
            $id_lang = $lang->getId();
            $Etag_treechart = "organigram:Rubrique~org-chart-page:$id_lang:$params_treechart";
            // we refresh the cache
            $this->cacheRefreshByname($Etag_treechart);
        }
    }    
    
    /**
     * Refresh all cache of a page.
     *
     * @param mixed       $page	       entity page or the id of the page
     * @param string      $lang        lang value
     * @param null|string $referer_url referer url value
     * 
     * @return void
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2014-04-08
     */
    public function cacheRefreshPage($page, $lang, $referer_url = null)
    {
    	// we get the id of the page.
    	if ($page instanceof \Sfynx\CmfBundle\Entity\Page ) {
            $id = $page->getId();
    	} else {
            $id = $page;
    	}
    	if (!is_null($referer_url)) {
            $name_page   = $this->createEtag('page', $id, $lang, array('page-url'=>$referer_url));
            $name_page   = str_replace('//', '/', $name_page);
            $this->cacheRefreshByname($name_page);
    	}
    	//print_r($name_page);
    	//print_r('<br />');print_r('<br />');
    	$path_json_file = $this->createJsonFileName('page', $id, $lang);
    	if (file_exists($path_json_file)) {
            $info = explode('|', file_get_contents($path_json_file));
            if (isset($info[1])) {
                $info[1] = \Sfynx\ToolBundle\Util\PiStringManager::cleanWhitespace($info[1]);
                $this->cacheRefreshByname($info[1]);
                //print_r($info[1]);
                //print_r('<br />');print_r('<br />');
            }
    	}
    	$path_json_file_sluggify = $this->createJsonFileName('page-sluggify', $id, $lang);
    	if (file_exists($path_json_file_sluggify)) {
            $reading  = fopen($path_json_file_sluggify, 'r');
            while (!feof($reading)) {
                $info = explode('|', fgets($reading));
                if (isset($info[1])) {
                    $info[1] = \Sfynx\ToolBundle\Util\PiStringManager::cleanWhitespace($info[1]);
                    $this->cacheRefreshByname($info[1]);
                    //print_r($info[1]);
                    //print_r('<br />');print_r('<br />');
                }
            }
            fclose($reading);
    	}
    	$path_json_file_history = $this->createJsonFileName('page-history', $id, $lang);
    	if (file_exists($path_json_file_history)) {
            $reading  = fopen($path_json_file_history, 'r');
            while (!feof($reading)) {
                $info = explode('|', fgets($reading));
                if (isset($info[1])) {
                    $info[1] = \Sfynx\ToolBundle\Util\PiStringManager::cleanWhitespace($info[1]);
                    $this->cacheRefreshByname($info[1]);
                    //print_r($info[1]);
                    //print_r('<br />');print_r('<br />');
                }
            }
            fclose($reading);
    	}    	
    }   
        
    /**
     * Refresh the cache of a widget.
     *
     * @param Widget $widget Widget entity
     * @param string $lang   Lang value
     * 
     * @return void
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2014-04-08
     */
    public function cacheRefreshWidget(Widget $widget, $lang)
    {
    	// we get the id of the widget.
    	$id = $widget->getId();
    	// we refesh only if the widget is in cash.
    	$Etag_widget  = 'widget:'.$id.':'.$lang;
    	// we refesh only if the widget is in cash.
    	$this->cacheRefreshByname($Etag_widget);
    	// we manage the "transwidget"
    	$params_transwidget = json_encode(array('widget-id'=>$id), JSON_UNESCAPED_UNICODE);
    	$widget_translations = $this->getWidgetManager()->setWidgetTranslations($widget);
    	if (is_array($widget_translations)) {
            foreach ($widget_translations as $translang => $translationWidget) {
                    // we create the cache name of the transwidget
                    $Etag_transwidget  = 'transwidget:'.$translationWidget->getId().':'.$translang.':'.$params_transwidget;
                    // we refresh the cache of the transwidget
                    $this->cacheRefreshByname($Etag_transwidget);
            }
    	}
        // If the widget is a "content snippet"
    	if ( ($widget->getPlugin() == 'content') && ($widget->getAction() == 'snippet') ) {
            $xmlConfig    = $widget->getConfigXml();
            // if the configXml field of the widget is configured correctly.
            try {
                $xmlConfig    = new \Zend_Config_Xml($xmlConfig);
                if ($xmlConfig->widgets->get('content')) {
                    $id_snippet    = $xmlConfig->widgets->content->id;
                    // we create the cache name of the snippet
                    $Etag_snippet  = 'transwidget:'.$id_snippet.':'.$lang.':'.$params_transwidget;
                    // we refresh the cache of the snippet
                    $this->cacheRefreshByname($Etag_snippet);
                }
            } catch (\Exception $e) {
            }
    	}
    	// If the widget is a "gedmo snippet"
    	if ( ($widget->getPlugin() == 'gedmo') && ($widget->getAction() == 'snippet') ) {
            $xmlConfig  = $widget->getConfigXml();
            $new_widget = null;
            // if the configXml field of the widget is configured correctly.
            try {
                $xmlConfig     = new \Zend_Config_Xml($xmlConfig);
                if ($xmlConfig->widgets->get('gedmo')) {
                    $id = $xmlConfig->widgets->gedmo->id;
                    // we refesh only if the widget is in cash.
                    $Etag_widget  = 'widget:'.$id.':'.$lang;
                    // we refesh only if the widget is in cash.
                    $this->cacheRefreshByname($Etag_widget);
                }
            } catch (\Exception $e) {
            }
    	}
    	$path_json_file = $this->createJsonFileName('widget', $id, $lang);
    	if (file_exists($path_json_file)) {
            $info = explode('|', file_get_contents($path_json_file));
            if (isset($info[1])) {
                $info[1] = \Sfynx\ToolBundle\Util\PiStringManager::cleanWhitespace($info[1]);
                $this->cacheRefreshByname($info[1]);
                //print_r($info[1]);
                //print_r('<br />');print_r('<br />');
            }
    	}
    	$path_json_file_history = $this->createJsonFileName('widget-history', $id, $lang);
    	if (file_exists($path_json_file_history)) {
            $reading  = fopen($path_json_file_history, 'r');
            while (!feof($reading)) {
                $info = explode('|', fgets($reading));
                if (isset($info[1])) {
                    $info[1] = \Sfynx\ToolBundle\Util\PiStringManager::cleanWhitespace($info[1]);
                    $this->cacheRefreshByname($info[1]);
                    //print_r($info[1]);
                    //print_r('<br />');print_r('<br />');
                }
            }
            fclose($reading);
    	}
        $path_json_file = $this->createJsonFileName('esi', $id, $lang);
    	if (file_exists($path_json_file)) {
            $reading  = fopen($path_json_file, 'r');
            while (!feof($reading)) {
                $info = explode('|', fgets($reading));
                if (isset($info[1])) {
                    // we get the esi url
                    $info[1] = \Sfynx\ToolBundle\Util\PiStringManager::cleanWhitespace($info[1]);
                    // we delete the cache widget file
                    $this->container->get("sfynx.cache.filecache")->getClient()->setPath($this->createCacheWidgetRepository());
                    $this->container->get("sfynx.cache.filecache")->clear($info[1]);
                    //print_r($id);print_r($info[1]);
                    //print_r('<br />');print_r('<br />');
                }
            }
            fclose($reading);
    	}  
    }    
    
    /**
     * Refresh the cache of all elements of a page (TranslationPages, widgets, translationWidgets)
     *
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-04-03
     */    
    public function cacheRefresh()
    {
        // we refresh the cache of the tree Chart page.
        $this->cacheTreeChartPageRefresh();
        // we get the current page.
        $page = $this->getCurrentPage();
        if (!is_null($page) && !is_null($this->translations[$page->getId()])) {
            foreach ($this->translations[$page->getId()] as $translation) {
                // we get the lang page
                $lang_page = $translation->getLangCode()->getId();
                // we refresh all caches of the page
                $referer_url = $this->container->get('sfynx.tool.route.factory')->getRefererRoute($lang_page, null, true);
                $referer_url = str_replace($this->container->get('request')->getUriForPath(''), '', $referer_url);
                $this->cacheRefreshPage($page, $lang_page, $referer_url);
                // we refresh the cache of all widgets of the page                             
                if (isset($this->widgets[$page->getId()]) && is_array($this->widgets[$page->getId()])) {
                    foreach ($this->widgets[$page->getId()] as $key_block=>$widgets) {
                        if (isset($this->widgets[$page->getId()][$key_block]) && is_array($this->widgets[$page->getId()][$key_block])) {
                            foreach ($this->widgets[$page->getId()][$key_block] as $key_widget => $widget) {
//                              print_r($this->container->get('request')->getLocale());
//                              print_r(' - id : ' . $widget->getId());
//                              print_r(' - plugin : ' . $widget->getPlugin());
//                              print_r(' - action : ' . $widget->getAction());
//                              print_r('<br />');                                
                                // we create the cache name of the widget
                            	$this->cacheRefreshWidget($widget, $lang_page);
                            } // endForeach
                        }
                    } // endForeach
                }
            } // endForeach
        }
    }
    
    /**
     * Delete the cache of all elements of an entity (Page, TranslationPages, widgets)
     *
     * @param object $entity Page or TranslationPage entity
     * @param string $type   ['persist', 'update', 'remove']
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2014-06-07
     */
    public function cachePage($entity, $type)
    {
        if ($entity instanceof TranslationPage) {
            $entity = $entity->getPage();
        }
        if ($entity instanceof Page) {
            $path_page_json_file = $this->createJsonFileName('page-json', $entity->getRouteName());
            if (in_array($type, array('persist', 'update'))) {
                $reports = serialize($entity);
                $result = \Sfynx\ToolBundle\Util\PiFileManager::save($path_page_json_file, $reports, 0777, LOCK_EX);
            } elseif ($type == 'remove') {
                if (file_exists($path_page_json_file)) {
                    unlink($path_page_json_file);
                }
            }
        }
    }
    
    /**
     * Delete the cache of all elements of an entity (Page, TranslationPages, widgets)
     *
     * @param object  $entity            An entity class
     * @param boolean $delete_cache_only True to delete only cache
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2014-06-07
     */
    public function cacheDelete($entity, $delete_cache_only)
    {
        if ($entity instanceof Page 
                || $entity instanceof TranslationPage 
                || $entity instanceof Widget
        ) {
            if ($entity instanceof TranslationPage) {
                $entity = $entity->getPage();
            }
            // we set the persist of the Page entity
            if ($entity instanceof Page) {
                $type = 'page';
            } elseif ($entity instanceof Widget) {
                $type = 'widget';
            }
            $names = array();
            $all_locales = $this->container->get('sfynx.auth.locale_manager')->getAllLocales();
            foreach ($all_locales as $key => $lang) {
                // we delete the cache of the page or the widget
                $path_json_file = $this->createJsonFileName($type, $entity->getId(), $lang);
                if (file_exists($path_json_file)) {
                    $info = explode('|', file_get_contents($path_json_file));
                    if (isset($info[1])) {
                        $this->cacheRefreshByname($info[1]);
                        // we delete the json file cache
                        if (!$delete_cache_only) {
                            unlink($path_json_file);
                        }
                    }
                }
                if ($type == 'page') {
                    // we delete the cache of all sluggify urls of the page.
                    $path_json_file_sluggify = $this->createJsonFileName('page-sluggify', $entity->getId(), $lang);
                    if (file_exists($path_json_file_sluggify)) {
                        $reading  = fopen($path_json_file_sluggify, 'r');
                        while (!feof($reading)) {
                            $info = explode('|', fgets($reading));
                            if (isset($info[1])) {
                                $this->cacheRefreshByname($info[1]);
                                $path_json_file_tmp = $this->createJsonFileName('page-sluggify-tmp', $info[1], $lang);
                                if (!$delete_cache_only && file_exists($path_json_file_tmp)) {
                                        unlink($path_json_file_tmp);
                                }
                            }
                        }
                        fclose($reading);
                        // we delete the json file cache
                        if (!$delete_cache_only) {
                                unlink($path_json_file_sluggify);
                        }
                    }
                    // we delete the cache of all history urls of the page.
                    $path_json_file_sluggify = $this->createJsonFileName('page-history', $entity->getId(), $lang);
                    if (file_exists($path_json_file_sluggify)) {
                        $reading  = fopen($path_json_file_sluggify, 'r');
                        while (!feof($reading)) {
                            $info = explode('|', fgets($reading));
                            if (isset($info[1])) {
                                $this->cacheRefreshByname($info[1]);
                                $path_json_file_tmp = $this->createJsonFileName('page-history-tmp', $info[1], $lang);
                                if (!$delete_cache_only && file_exists($path_json_file_tmp)) {
                                        unlink($path_json_file_tmp);
                                }
                            }
                        }
                        fclose($reading);
                        // we delete the json file cache
                        if (!$delete_cache_only) {
                            unlink($path_json_file_sluggify);
                        }
                    }
                    // we delete the cache of all esi tag urls of the page.
                    $path_json_file_esi = $this->createJsonFileName('esi', $entity->getId(), $lang);
                    if (file_exists($path_json_file_sluggify)) {
                        $reading  = fopen($path_json_file_sluggify, 'r');
                        while (!feof($reading)) {
                            $info = explode('|', fgets($reading));
                            if (isset($info[1])) {
                                $this->cacheRefreshByname($info[1]);
                                $path_json_file_tmp = $this->createJsonFileName('esi-tmp', $info[1], $lang);
                                if (!$delete_cache_only && file_exists($path_json_file_tmp)) {
                                        unlink($path_json_file_tmp);
                                }
                            }
                        }
                        fclose($reading);
                        // we delete the json file cache
                        if (!$delete_cache_only) {
                            unlink($path_json_file_esi);
                        }
                    }
                } elseif ($type == 'widget') {
                    // we delete the cache of all history urls of the page.
                    $path_json_file_sluggify = $this->createJsonFileName('widget-history', $entity->getId(), $lang);
                    if (file_exists($path_json_file_sluggify)) {
                        $reading  = fopen($path_json_file_sluggify, 'r');
                        while (!feof($reading)) {
                            $info = explode('|', fgets($reading));
                            if (isset($info[1])) {
                                $this->cacheRefreshByname($info[1]);
                                $path_json_file_tmp = $this->createJsonFileName('widget-history-tmp', $info[1], $lang);
                                if (!$delete_cache_only && file_exists($path_json_file_tmp)) {
                                        unlink($path_json_file_tmp);
                                }
                            }
                        }
                        fclose($reading);
                        // we delete the json file cache
                        if (!$delete_cache_only) {
                                unlink($path_json_file_sluggify);
                        }
                    }
                }
            }
        }        
    }
        
    
    /**
     * Copy the page with all elements of a page (TranslationPages, widgets, translationWidgets, block)
     * 
     * @param string $locale locale value
     * 
     * @return string the new url of the page
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-04-03
     */
    public function copyPage($locale = '')
    {
    	$em = $this->getContainer()
                ->get('doctrine')
                ->getManager();    	
    	if (empty($locale)) {
            $locale = $this->getContainer()
                    ->get('request')
                    ->getLocale();
    	}
    	// we get the current page.
    	$page = $this->getCurrentPage();
    	$id = $page->getId();
    	if (!is_null($page) && !is_null($this->translations[$id])) {    
            $eventManager = $em->getEventManager();
            $eventManager->removeEventListener(
                array('prePersist'),
                $this->getContainer()->get('pi_app_admin.prepersist_listener')
            );
            $eventManager->removeEventListener(
                array('postPersist'),
                $this->getContainer()->get('pi_app_admin.postpersist_listener')
            );
            $eventManager->removeEventListener(
                array('preUpdate'),
                $this->getContainer()->get('pi_app_admin.preupdate_listener')
            );
            $eventManager->removeEventListener(
                array('postUpdate'),
                $this->getContainer()->get('pi_app_admin.postupdate_listener')
            );
            //    		
            $new_page = clone($page);
            $new_page->setId(null);
            if ($page->getLayout() instanceof \Sfynx\AuthBundle\Entity\Layout) {
                $new_page->setLayout($em->getReference('Sfynx\AuthBundle\Entity\Layout', $page->getLayout()->getId()));
            } else {
                $new_page->setLayout(null);
            }
            $new_page->setPageCss(new \Doctrine\Common\Collections\ArrayCollection());
            $new_page->setPageJs(new \Doctrine\Common\Collections\ArrayCollection());
            if ($page->getUser() instanceof \Sfynx\AuthBundle\Entity\User) {
                $new_page->setUser($em->getReference('Sfynx\AuthBundle\Entity\User', $page->getUser()->getId()));
            } else {
                $new_page->setUser(null);
            }
            if ($page->getRubrique() instanceof \Sfynx\CmfBundle\Entity\Rubrique) {
                $new_page->setRubrique($em->getReference('Sfynx\CmfBundle\Entity\Rubrique', $page->getRubrique()->getId()));
            } else {
                $new_page->setRubrique(null);
            }
            $new_page->setTranslations(new \Doctrine\Common\Collections\ArrayCollection());
            // we copy all translations.
            foreach ($this->translations[$id] as $translation) {
                $new_translation = clone($translation);
                $new_translation->setId(null);
                $new_translation->setTags(new \Doctrine\Common\Collections\ArrayCollection());
                if ($translation->getLangCode() instanceof \Sfynx\AuthBundle\Entity\Langue) {
                    $new_translation->setLangCode($em->getReference('Sfynx\AuthBundle\Entity\Langue', $translation->getLangCode()->getId()));
                } else {
                    $new_translation->setLangCode(null);
                }
                $new_page->addTranslation($new_translation);
            }
            // we clone all blocks and all widgets.   		
            if (isset($this->blocks[$id]) && !empty($this->blocks[$id])) {
                $all_blocks = $this->blocks[$id];
                foreach ($all_blocks as $block) {
                    $new_block = clone($block);
                    $new_block->setId(null);
                    // if the block is not disabled.
                    if ($block->getEnabled()) {    	
                        // we set all widget of the block
                        if (isset($this->widgets[$id][$block->getId()]) && !empty($this->widgets[$id][$block->getId()])){
                            $all_widgets      = $this->widgets[$id][$block->getId()];
                            foreach ($all_widgets as $widget) {
                                if ($widget->getEnabled()) {
                                    $new_widget = clone($widget);
                                    $new_widget->setId(null);
                                    $new_block->addWidget($new_widget);
                                }
                            }
                        }
                    }
                    $new_page->addBlock($new_block);
                }
            }
            // we change the route name of the new page.
            $randome = new \DateTime();
            $new_page->setRouteName($page->getRouteName() . '_copy_' . $randome->getTimeStamp());
            $new_page->setUrl($page->getUrl() . '/copy/' . $randome->getTimeStamp());
            // we persist.
            $em->persist($new_page);
            $em->flush();
            // we register the new page in the route cache manager.
            $routeCacheManager = $this->getContainer()->get('sfynx.tool.route.cache');
            $routeCacheManager->setGenerator();
            $routeCacheManager->setMatcher();
            
            // we set the new url in the locale.
            return  $this->getContainer()
                    ->get('sfynx.tool.route.factory')
                    ->getRoute('pi_routename_redirection', array('routename' => $new_page->getRouteName(), 'langue' => $locale));
    	}
    	
    	return $this->getContainer()
                ->get('sfynx.tool.route.factory')
                ->getRoute('home_page', array('locale' => $locale));
    }
    
    /**
     * Refresh the cache of all elements of a page (TranslationPages, widgets, translationWidgets)
     *
     * @param string $type   ['page', 'block', 'widget']
     * @param string $entity Entity
     * 
     * @return string Returns the requested url.
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-04-03
     */
    public function getUrlByType($type, $entity = null)
    {
        $Url    = null;
        //
        switch ($type) {
            case 'page':
                if (is_int($entity)) {
                    $entity = $this->getPageById($entity);
                }
                if ($entity instanceof Page){
                    $Url['edit']         = $this->container->get('router')->generate('admin_pagebytrans_edit', array('id' => $entity->getId(), 'NoLayout' => true));
                }                
                $Url['new']         = $this->container->get('router')->generate('admin_pagebytrans_new', array('NoLayout' => true));            
                break;            
            case 'block':
                if (is_int($entity)) {
                    $entity = $this->getBlockById($entity);
                }
                if ($entity instanceof Block){                
                    $Url['admin']     = $this->container->get('router')->generate('admin_blockbywidget_show', array('id' => $entity->getId(), 'NoLayout' => true));
                    $Url['import']    = $this->container->get('router')->generate('public_importmanagement_widget', array('id_block' => $entity->getId(), 'NoLayout' => true));
                }                
                break;
            case 'widget':
                if (is_null($entity)) {
                    $entity = $this->getCurrentWidget();
                }
                if (is_int($entity)) {
                    $entity = $pageManager->getWidgetById($entity);
                }
                if ($entity instanceof Widget) {
                    $Url['move_up']   = $this->container->get('router')->generate('admin_widget_move_ajax', array('id' => $entity->getId(), 'type' => 'up'));
                    $Url['move_down'] = $this->container->get('router')->generate('admin_widget_move_ajax', array('id' => $entity->getId(), 'type' => 'down'));
                    $Url['delete']    = $this->container->get('router')->generate('admin_widget_delete_ajax', array('id' => $entity->getId()));
                    $Url['admin']     = $this->container->get('router')->generate('admin_widget_edit', array('id' => $entity->getId(), 'NoLayout' => true));
                    $Url['edit']      = $this->container->get('router')->generate('admin_homepage');
                    $Url['import']    = $this->container->get('router')->generate('public_importmanagement_widget', array('id_widget' => $entity->getId(), 'NoLayout' => true));
                    try {
                        $xmlConfig    = $entity->getConfigXml();
                        $xmlConfig    = new \Zend_Config_Xml($xmlConfig);
                        ////////////////// url management of gedmo snippet ///////////////////////////
                        if ( ($entity->getPlugin() == "gedmo") && $xmlConfig->widgets->get('gedmo') && $xmlConfig->widgets->gedmo->get('snippet') && $xmlConfig->widgets->gedmo->get('id') ){
                            $id_snippet = $xmlConfig->widgets->gedmo->get('id');
                            $is_snippet = $xmlConfig->widgets->gedmo->get('snippet');
                            if ($is_snippet && !empty($id_snippet)){
                                $entity = $this->getWidgetById($id_snippet);
                                $xmlConfig   = $entity->getConfigXml();
                                $xmlConfig   = new \Zend_Config_Xml($xmlConfig);     
                                $Url['admin'] 		= $this->container->get('router')->generate('admin_widget_edit', array('id' => $id_snippet, 'NoLayout' => true));
                            }
                        }
                        ////////////////// url management of all gedmo widget ///////////////////////////
                        if ( ($entity->getPlugin() == "gedmo") && $xmlConfig->widgets->get('gedmo') && $xmlConfig->widgets->gedmo->get('controller')) {
                            $infos        = explode(':', $xmlConfig->widgets->gedmo->controller);
                            $infos_entity = $infos[0] . ':' . str_replace('\\\\', '\\', $infos[1]);
                            $infos_method = strtolower($infos[2]);
                            $getAvailable = "getAvailable" . ucfirst(strtolower($entity->getAction()));                            
                            try {
                                $Lists    = \Sfynx\CmfBundle\Util\PiWidget\PiGedmoManager::$getAvailable();
                            } catch (\Exception $e) {
                                $Lists = null;
                            }                            
//                             if ( $xmlConfig->widgets->gedmo->get('params') && $xmlConfig->widgets->gedmo->params->get('id') )
//                                 $params['id']        = $xmlConfig->widgets->gedmo->params->id;
//                             if ( $xmlConfig->widgets->gedmo->get('params') && $xmlConfig->widgets->gedmo->params->get('category') )
//                                 $params['category']    = $xmlConfig->widgets->gedmo->params->category;
                            $params['NoLayout']    = true;
                            if ( $xmlConfig->widgets->gedmo->get('params')) {
                                $params = array_merge($xmlConfig->widgets->gedmo->params->toArray(), $params);                            
                            }
                            //if (isset($Lists[$infos_entity][$infos_method]['edit']))
                            //    $Url['edit']         = $this->container->get('router')->generate($Lists[$infos_entity][$infos_method]['edit'], $params);
                            if (isset($Lists[$infos_entity][$infos_method]) && is_array($Lists[$infos_entity][$infos_method])) {
                                foreach($Lists[$infos_entity][$infos_method] as $action => $route_name){
                                    $Url[$action] = $this->container->get('router')->generate($route_name, $params);
                                }
                            }
                        }
                        ////////////////// url management of translation content widget ///////////////////////////
                        if ( ($entity->getPlugin() == "content") && $xmlConfig->widgets->get('content') ) {
                            if ( $xmlConfig->widgets->content->get('snippet') && $xmlConfig->widgets->content->get('id') ) {
                                $Url['edit'] = $this->container->get('router')->generate('admin_widget_edit', array('id' => $xmlConfig->widgets->content->get('id'), 'NoLayout' => true));
                            }
                        }
                        ////////////////// url management of all content widget ///////////////////////////
                        if ( ($entity->getPlugin() == "content") && $xmlConfig->widgets->get('content') && $xmlConfig->widgets->content->get('controller') ) {
                            $infos        = $xmlConfig->widgets->content->controller;
                            $getAvailable = "getAvailable" . ucfirst(strtolower($entity->getAction()));                            
                            if ($xmlConfig->widgets->content->get('params') && $xmlConfig->widgets->content->params->get('action')) {
                                $infos_method = $xmlConfig->widgets->content->params->action;
                            }
                            try {
                                $Lists = \Sfynx\CmfBundle\Util\PiWidget\PiContentManager::$getAvailable();
                            } catch (\Exception $e) {
                                $Lists = null;
                            }                            
                            $params['NoLayout'] = true;
                            if ( $xmlConfig->widgets->content->get('params')) {
                                $params = array_merge($xmlConfig->widgets->content->params->toArray(), $params);                        
                            }
                            if (isset($Lists[$infos][$infos_method]) && is_array($Lists[$infos][$infos_method])) {
                                foreach($Lists[$infos][$infos_method] as $action => $route_name) {
                                    $Url[$action] = $this->container->get('router')->generate($route_name, $params);
                                }
                            }
                        }                        
                    } catch (\Exception $e) {
                    }
                }
                break;
        } // end switch
                
        return $Url;
    }    
    
    /**
     * Return all urls of a page
     * 
     * @param Page   $page Page entity
     * @param string $type ['sql']
     * 
     * @return array
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-06-21
     */
    public function getUrlByPage(Page $page, $type = '')
    {        
        $urls = array();
        // we register all urls of the page
        foreach($page->getTranslations() as $key=>$translationPage) {
            if ($translationPage instanceof TranslationPage) {
                $locale = $translationPage->getLangCode()->getId();
                $url    = $page->getUrl();
                $slug   = $translationPage->getSlug();
                if (!empty($url)
                        && !empty($slug)
                ) {
                    $urls[$locale] = $url . "/" .$slug;
                } elseif (!empty($url)
                        && empty($slug)
                ) {
                    $urls[$locale] = $url;
                } elseif (empty($url)
                        && !empty($slug)
                ) {
                    $urls[$locale] = $slug;
                } elseif (empty($url)
                        && empty($slug)
                ) {
                    $urls[$locale] = "";
                }
                $is_prefix_locale = $this->container
                        ->getParameter("pi_app_admin.page.route.with_prefix_locale");
                if ($is_prefix_locale) {
                    $locale_tmp = explode('_', $locale);
                    $urls[$locale] = $locale_tmp[0] . '/' . $urls[$locale];
                }
                $urls[$locale]     = str_replace("//","/",$urls[$locale]);                
                if ($type == 'sql') {
                    $urls[$locale] = str_replace("/","\\\\\\\\\/",$urls[$locale]);
                }
            }
        }

        return $urls;
    }        
}
