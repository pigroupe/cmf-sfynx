<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Managers
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-23
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

use Sfynx\CmfBundle\Builder\PiWidgetManagerBuilderInterface;
use Sfynx\CmfBundle\Manager\PiCoreManager;
use Sfynx\CmfBundle\Entity\Widget;
use Sfynx\CmfBundle\Entity\TranslationWidget;

/**
 * Description of the Widget manager
 *
 * @subpackage   Admin_Managers
 * @package    Manager
 * 
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiWidgetManager extends PiCoreManager implements PiWidgetManagerBuilderInterface 
{    
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
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
     * Returns the render source of a widget.
     *
     * @param int         $id        id widget
     * @param string     $lang    language
     *
     * @return string    widget content
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-02-15
     */
    public function exec($id, $lang = "")
    {
        if (!empty($lang)) {
            $this->language = $lang;
        }        
        // we get the current Widget.
        $widget     = $this->getRepository('Widget')->findOneById($id);        
        // we set the current result
        $this->setCurrentWidget($widget);    
        // we return the render (cache or not)
        return $this->render($this->language);
    }    
    
    /**
     * Returns the render of the current widget.
     *
     * @param string $lang
     * 
     * @return string
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-01-23
     */
    public function render($lang = '')
    {
        // we set the langue
        if (empty($lang))    $lang = $this->language;        
        //     Initialize widget
        if ($this->getCurrentWidget()) {
            $widget = $this->getCurrentWidget();
        } else {
            throw new \InvalidArgumentException("you don't have set the current widget !");
        }        
        //     Initialize response
        $response = $this->getResponseByIdAndType('widget', $widget->getId());        
        // we get the translation of the current widget in terms of the lang value.
        // $widgetTrans        = $this->getTranslationByWidgetId($widget->getId(), $lang);      
        // Handle 404
        // We don't show the widget if :
        // * the widget doesn't exist.
        // * The widget doesn't have a translation set.
        if (!$widget || !$this->isWidgetSupported($widget)) {
            $transWidgetError     = $this->getRepository('translationWidget')->getTranslationByParams(1, 'content', 'error', $lang);
            if (!$transWidgetError) {
                throw new \InvalidArgumentException("We haven't set in the data fixtures the error widget message in the $lang locale !");
            }            
            $response->setStatusCode(404);            
            // We set the Etag value
            $id          = $transWidgetError->getId();
            $this->setEtag("transwidget:$id:$lang");            
            // create a Response with a Last-Modified header
            $response    = $this->configureCache($transWidgetError, $response);            
        } else {
            // We set the Etag value
            $id          = $widget->getId();
            $this->setEtag("widget:$id:$lang");            
            // create a Response with a Last-Modified header
            $response    = $this->configureCache($widget, $response);            
        }        
        // Check that the Response is not modified for the given Request
        if ($response->isNotModified($this->container->get('request'))) {
            // return the 304 Response immediately
            return $response;
        } else {
            // if the widget has translation OR if the widget calls a snippet
            if ( $widget && $this->isWidgetSupported($widget) ) {
                $response = $this->container->get('pi_app_admin.caching')->renderResponse($this->Etag, array(), $response);
                // We set the reponse
                $this->setResponse($widget, $response);
            } else {
                // or render the error template with the $response you've already started
                $response = $this->container->get('pi_app_admin.caching')->renderResponse($this->Etag, array(), $response);
            }
            // we don't send the header but the content only.
            return $response->getContent();
        }
    }
    
    /**
     * Returns the render source of one widget.
     *
     * @param string $id
     * @param string $lang
     * @param array  $params
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2014-07-21
     */
    public function renderSource($id, $lang = '', $params = null)
    {
        // we get the translation of the current page in terms of the lang value.
        $this->getWidgetById($id);  
        $container       = $this->getCurrentWidget()->getPlugin();
        $NameAction      = $this->getCurrentWidget()->getAction();
        $id              = $this->getCurrentWidget()->getId();
        $cssClass        = $this->container->get('sfynx.tool.string_manager')->slugify($this->getCurrentWidget()->getConfigCssClass());
        $lifetime  	 = $this->getCurrentWidget()->getLifetime();
        $cacheable 	 = strval($this->getCurrentWidget()->getCacheable());
        $update    	 = $this->getCurrentWidget()->getUpdatedAt()->getTimestamp();
        $public    	 = strval($this->getCurrentWidget()->getPublic());
        $cachetemplating = strval($this->getCurrentWidget()->getCacheTemplating());
        $sluggify  	 = strval($this->getCurrentWidget()->getSluggify());
        $ajax      	 = strval($this->getCurrentWidget()->getAjax());
        $is_secure	 = $this->getCurrentWidget()->getSecure();
        $heritage	 = $this->getCurrentWidget()->getHeritage();
        
//        $configureXml    = $this->container->get('sfynx.tool.string_manager')->filtreString($this->getCurrentWidget()->getConfigXml());
//        $options = array(
//            'widget-id' => $id
//        );
//        $source = $this->extensionWidget->FactoryFunction(strtoupper($container), strtolower($NameAction), $options);
        
        // get secure value
        $if_script    = "";
        $endif_script = "";
        if ( $is_secure && !is_null($heritage) && (count($heritage) > 0) ) {
            $heritages_info = array_merge($heritage, $this->container->get('sfynx.auth.role.factory')->getNoAuthorizeRoles($heritage));
            if ( !is_null($heritages_info) ) {
                $if_script      = $heritages_info['twig_if'];
                $endif_script   = $heritages_info['twig_endif'];
            }
        }
        $source  = $if_script;
        if (!empty($cssClass)) {
            $source .= " <div class=\"{$cssClass}\"> \n";
        } else {
            $source .= " <div> \n";
        }        		
        $source .= "     {% set options = {'widget-id': '$id', 'widget-lang': '$lang', 'widget-lifetime': '$lifetime', 'widget-cacheable': '$cacheable', 'widget-update': '$update', 'widget-public': '$public', 'widget-cachetemplating': '$cachetemplating', 'widget-ajax': '$ajax', 'widget-sluggify': '$sluggify'} %} \n";
        $source .= "     {{ renderWidget('".strtoupper($container)."', '".strtolower($NameAction)."', options )|raw }} \n";
        $source .= " </div> \n";
        $source .= $endif_script;
        
        return $source;
    }
    
    /**
     * Returns the render source of a tag by the twig cache service.
     *
     * @param string $tag
     * @param string $id
     * @param string $lang
     * @param array  $params
     *
     * @return string    extension twig result
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2012-04-19
     */
    public function renderCache($serviceName, $tag, $id, $lang, $params = null)
    {
        // we create the twig code of the service to rn.
        if (!is_null($params)) {
            if (isset($params['widget-sluggify']) && ($params['widget-sluggify'] == true)) {
                $params['widget-sluggify-url']    = $this->container->get('request')->getUri();
            }            
            $json = $this->container->get('sfynx.tool.string_manager')->json_encodeDecToUTF8($params);            
            $set = " {{ getService('$serviceName').run('$tag', '$id', '$lang', {$json})|raw }} \n";
        } else {
            $set = " {{ getService('$serviceName').run('$tag', '$id', '$lang')|raw }} \n";
        } 
        // we register the tag value in the json file if does not exist.
        $this->setJsonFileEtag($tag, $id, $lang, $params);
        
        return $set;
    }
    
    /**
     * Returns the render source of a service manager.
     *
     * @param string $id
     * @param string $lang
     * @param array  $params
     *
     * @return string extension twig result
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2012-04-19
     */
    public function renderService($serviceName, $id, $lang, $params = null)
    {
        if (!isset($params['locale']) || empty($params['locale'])) {
            $params['locale']    = $lang;
        }
        // get params
        $is_render_service_with_ttl    = $this->container->getParameter('pi_app_admin.page.widget.render_service_with_ttl');
        $is_render_service_with_ajax   = $this->container->getParameter('pi_app_admin.page.widget.render_service_with_ajax');
        $is_render_service_for_varnish = $this->container->getParameter('pi_app_admin.page.esi.force_widget_tag_esi_for_varnish');
        $esi_key                       = $this->container->getParameter('pi_app_admin.page.esi.encrypt_key');
        //
        if (!is_null($params)) {
            $this->container->get('sfynx.tool.array_manager')->recursive_method($params, 'krsort');
            $json = $this->container->get('sfynx.tool.string_manager')->json_encodeDecToUTF8($params); 
            //
            $esi_method      = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter('renderSource', $esi_key);
            $esi_serviceName = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter($serviceName, $esi_key);
            $esi_id          = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter($id, $esi_key);
            $esi_lang        = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter($lang, $esi_key);
            $esi_json        = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter($json, $esi_key);
            // we get query string
            if (null !== $qs = $this->container->get('request')->getQueryString()) {
                $qs = '?'.$qs;
            } else {
                $qs = '';
            }
            //
            $_server_ = array(
                'REQUEST_URI'  => $this->container->get('request')->getRequestUri(),
                'REDIRECT_URL' => $this->container->get('request')->server->get('REDIRECT_URL'),
                'lifetime'     => $params['widget-lifetime'],
                'cacheable'    => $params['widget-cacheable'],
                'update'       => $params['widget-update'],
                'public'       => $params['widget-public'],
            );
            $esi_server      = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter(json_encode($_server_, JSON_UNESCAPED_UNICODE), $esi_key);
            // url
            $url = $this->container->get('sfynx.tool.route.factory')->getRoute('public_esi_apply_widget', array(
                'method'        =>$esi_method,
                'serviceName'   =>$esi_serviceName,
                'id'            =>$esi_id,
                'lang'          =>$esi_lang,
                'params'        =>$esi_json,
                'key'           =>$esi_key,
                'server'        =>$esi_server
            ));
            //
            $isEsi = $this->container->getParameter('pi_app_admin.page.esi.authorized');
            if ($isEsi) {
                $is_esi_activate = true;
            } else {
            	$is_esi_activate = false;
            }
            $ttl = (int) $params['widget-lifetime'];
            if (($ttl > 0)
            	&& ($is_esi_activate
                        || $is_render_service_with_ajax
                        || (isset($params['widget-ajax'])
                                && ($params['widget-ajax'] == true)
                           ) 
                    )
            ) {
                if ($is_esi_activate) {
                    $set  = "{% if is_esi_disable_after_post_request and (app_request_request_count >= 1) %}\n";
                    $set .= "    {{ getService('{$serviceName}').renderSource('{$id}', '{$lang}', {$json})|raw }}\n";
                    $set .= "{% else %}\n";
                    if ($is_render_service_for_varnish) {                   
                        $set .= "    <esi:include src=\"{$url}{$qs}\" />\n";
                    } else {
                        $set .= " {{ render_esi(\"{$url}{$qs}\")|raw }} \n";
                    }
                    $set .= "{% endif %}\n";
                } elseif ( $is_render_service_with_ajax || (isset($params['widget-ajax']) && ($params['widget-ajax'] == true)) ) {
                    $set  = "{% if is_widget_ajax_disable_after_post_request and (app_request_request_count >= 1) %}\n";
                    $set .= "    {{ getService('{$serviceName}').renderSource('{$id}', '{$lang}', {$json})|raw }}\n";
                    $set .= "{% else %}\n";
                    $set .= "    <span class=\"hiddenLinkWidget {{ '{$url}{$qs}'|obfuscateLink }}\" />\n";
                    $set .= "{% endif %}\n";
                }
            } else {
                if ($is_render_service_with_ttl && ($ttl > 0)) {
                    $set = " {{ renderCache('{$url}{$qs}', '{$ttl}', '{$serviceName}', 'renderSource', '{$id}', '{$lang}', {$json})|raw }}\n";
                } else {
                    $set = " {{ getService('{$serviceName}').renderSource('{$id}', '{$lang}', {$json})|raw }}\n";
                }
            }
            // we register the tag value in the json file if does not exist.
            if (isset($params['widget-id'])) {
                $this->setJsonFileEtag('esi', $params['widget-id'], $lang, array('esi-url'=>"{$url}{$qs}"));
            } else {
                $this->setJsonFileEtag('esi', $serviceName, $lang, array('esi-url'=>"{$url}{$qs}"));
            }                        
        } else {
            $set = " {{ getService('{$serviceName}').renderSource('{$id}', '{$lang}')|raw }}\n";
        }        
    
        return $set;
    } 

    /**
     * Returns the render source of a jquery extension.
     *
     * @param string    $JQcontainer
     * @param string    $id
     * @param string    $lang
     * @param array     $params
     *
     * @return string    extension twig result
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-06-01
     */
    public function renderJquery($JQcontainer, $id, $lang, $params = null)
    {
        str_replace('~', '~', $id, $count);
        if ($count == 2) {
            list($entity, $method, $category) = explode('~', $id);
        } elseif ($count == 1) {
            list($entity, $method) = explode('~', $id);
        } elseif ($count == 0) {
            $method = $id;
        } else {
            throw new \InvalidArgumentException("you have not configure correctly the attibute id");
        }        
        if (!isset($params['locale']) || empty($params['locale'])) {
            $params['locale']    = $lang;
        }                
        // get params
        $is_render_service_with_ttl    = $this->container->getParameter('pi_app_admin.page.widget.render_service_with_ttl');
        $is_render_service_with_ajax   = $this->container->getParameter('pi_app_admin.page.widget.render_service_with_ajax');
        $is_render_service_for_varnish = $this->container->getParameter('pi_app_admin.page.esi.force_widget_tag_esi_for_varnish');
        $esi_key                       = $this->container->getParameter('pi_app_admin.page.esi.encrypt_key');
        //
        if (!is_null($params)) {
            $this->container->get('sfynx.tool.array_manager')->recursive_method($params, 'krsort');
            $json = $this->container->get('sfynx.tool.string_manager')->json_encodeDecToUTF8($params);
            // set url of the esi page
            $esi_method      = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter('FactoryFunction', $esi_key);
            $esi_serviceName = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter("sfynx.tool.twig.extension.jquery", $esi_key);
            $esi_id          = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter($JQcontainer, $esi_key);
            $esi_lang        = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter($method, $esi_key);
            $esi_json        = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter($json, $esi_key);
            // we get query string
            if (null !== $qs = $this->container->get('request')->getQueryString()) {
                $qs = '?'.$qs;
            } else {
                $qs = '';
            }
            $_server_ = array(
                    'REQUEST_URI'  => $this->container->get('request')->getRequestUri(),
                    'REDIRECT_URL' => $this->container->get('request')->server->get('REDIRECT_URL'),
                    'lifetime'     => $params['widget-lifetime'],
                    'cacheable'    => $params['widget-cacheable'],
                    'update'       => $params['widget-update'],
                    'public'       => $params['widget-public'],
            );
            $esi_server      = $this->container->get('sfynx.tool.twig.extension.tool')->encryptFilter(json_encode($_server_, JSON_UNESCAPED_UNICODE), $esi_key);
            // url
            $url = $this->container->get('sfynx.tool.route.factory')->getRoute('public_esi_apply_widget', array(
                    'method'        =>$esi_method,
                    'serviceName'   =>$esi_serviceName,
                    'id'            =>$esi_id,
                    'lang'          =>$esi_lang,
                    'params'        =>$esi_json,
                    'key'           =>$esi_key,
                    'server'        =>$esi_server
            ));
            $isEsi = $this->container->getParameter('pi_app_admin.page.esi.authorized');
            if ($isEsi) {
                $is_esi_activate = true;
            } else {
            	$is_esi_activate = false;
            }            
            $ttl = (int) $params['widget-lifetime'];
            if (($ttl > 0)
            	&& ($is_esi_activate 
                        || $is_render_service_with_ajax
                        || (isset($params['widget-ajax'])
                                && ($params['widget-ajax'] == true)
                           )
                    )
            ) {
                if($is_esi_activate) {
                    $set  = "{% if is_esi_disable_after_post_request and (app_request_request_count >= 1) %}\n";
                    $set .= "    {{ getService('sfynx.tool.twig.extension.jquery').FactoryFunction('{$JQcontainer}', '{$method}', {$json})|raw }}\n";
                    $set .= "{% else %}\n";
                    if ($is_render_service_for_varnish) {                   
                        $set .= "    <esi:include src=\"{$url}{$qs}\" />\n";
                    } else {
                        $set .= " {{ render_esi(\"{$url}{$qs}\")|raw }} \n";
                    }
                    $set .= "{% endif %}\n";
                } elseif ( $is_render_service_with_ajax || (isset($params['widget-ajax']) && ($params['widget-ajax'] == true)) ) {
                    $set  = "{% if is_widget_ajax_disable_after_post_request and (app_request_request_count >= 1) %}\n";
                    $set .= "    {{ getService('sfynx.tool.twig.extension.jquery').FactoryFunction('{$JQcontainer}', '{$method}', {$json})|raw }}\n";
                    $set .= "{% else %}\n";
                    $set .= "    <span class=\"hiddenLinkWidget {{ '{$url}{$qs}'|obfuscateLink }}\" />\n";
                    $set .= "{% endif %}\n";
                }
            } else {          
                if ($is_render_service_with_ttl && ($ttl > 0)) {
                    $set = " {{ renderCache('{$url}{$qs}', '{$ttl}', 'sfynx.tool.twig.extension.jquery', 'FactoryFunction', '{$JQcontainer}', '{$method}', {$json})|raw }}\n";
                } else {
                    $set = " {{ getService('sfynx.tool.twig.extension.jquery').FactoryFunction('{$JQcontainer}', '{$method}', {$json})|raw }}\n";
                }
            }
            // we register the tag value in the json file if does not exist.
            if (isset($params['widget-id'])) {
                $this->setJsonFileEtag('esi', $params['widget-id'], $lang, array('esi-url'=>"{$url}{$qs}"));
            } else {
                $this->setJsonFileEtag('esi', $JQcontainer, $lang, array('esi-url'=>"{$url}{$qs}"));
            }            
        } else {
            $set = " {{ getService('sfynx.tool.twig.extension.jquery').FactoryFunction('{$JQcontainer}', '{$method}')|raw }}\n";
        }
    
        return $set;
    }   
    
    
    /**
     * Sets js and css script of the widget.
     *
     * @return void
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-02-16
     */
    public function setScript()
    {
        $container  = strtoupper($this->getCurrentWidget()->getPlugin());
        $NameAction = strtolower($this->getCurrentWidget()->getAction());        
        // If the widget is a "gedmo snippet"
        if (($container == 'CONTENT') 
                && ($NameAction == 'snippet')
        ) {
            // if the configXml field of the widget is configured correctly.
            try {
                $xmlConfig    = new \Zend_Config_Xml($this->getCurrentWidget()->getConfigXml());
                if ($xmlConfig->widgets->get('content')){
                    $snippet_widget = $this->getWidgetById($xmlConfig->widgets->content->id);
                    $container      = strtoupper($snippet_widget->getPlugin());
                    $NameAction     = strtolower($snippet_widget->getAction());
                }
            } catch (\Exception $e) {
            }             
        }
        // If the widget is a "gedmo snippet"
        elseif (($container == 'GEDMO') 
                && ($NameAction == 'snippet')
        ) {
            // if the configXml field of the widget is configured correctly.
            try {
                $xmlConfig    = new \Zend_Config_Xml($this->getCurrentWidget()->getConfigXml());
                if ($xmlConfig->widgets->get('gedmo')){
                    $snippet_widget = $this->getWidgetById($xmlConfig->widgets->gedmo->id);
                    $container      = strtoupper($snippet_widget->getPlugin());
                    $NameAction     = strtolower($snippet_widget->getAction());
                }
            } catch (\Exception $e) {
            }
        }      
        $this->script['js'][$container.$NameAction]  = $this->extensionWidget
                ->ScriptJsFunction($container, $NameAction);
        $this->script['css'][$container.$NameAction] = $this->extensionWidget
                ->ScriptCssFunction($container, $NameAction);
    }

    /**
     * Sets init the widget.
     *
     * @return string
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-02-16
     */
    public function setInit()
    {
        $container  = strtoupper($this->getCurrentWidget()->getPlugin());
        $NameAction = strtolower($this->getCurrentWidget()->getAction());
        $method     = ":";        
        $xmlConfig  = $this->getCurrentWidget()->getConfigXml();        
        // if the configXml field of the widget isn't configured correctly.
        try {
            $xmlConfig    = new \Zend_Config_Xml($xmlConfig);
        } catch (\Exception $e) {
            return "  \n";
        }        
        // we add all css files.
        if ( $xmlConfig->widgets->get('css') ){
        	if (is_object($xmlConfig->widgets->css)) {
        		$all_css = $xmlConfig->widgets->css->toArray();
        		$this->script['init'][$container.$NameAction.$method.'css'] =  "{% initWidget('css:".json_encode($all_css, JSON_UNESCAPED_UNICODE)."') %}";
        	} elseif (is_string($xmlConfig->widgets->css)) {
        		$this->script['init'][$container.$NameAction.$method.'css'] =  "{% initWidget('css:".json_encode(array($xmlConfig->widgets->css), JSON_UNESCAPED_UNICODE)."') %}";
        	}
        }
        // we add all js files.
            if ( $xmlConfig->widgets->get('js') ){
        	if (is_object($xmlConfig->widgets->js)) {
        		$all_js = $xmlConfig->widgets->js->toArray();
        		$this->script['init'][$container.$NameAction.$method.'js'] =  "{% initWidget('js:".json_encode($all_js, JSON_UNESCAPED_UNICODE)."') %}";
        	} elseif (is_string($xmlConfig->widgets->js)) {
        		$this->script['init'][$container.$NameAction.$method.'js'] =  "{% initWidget('js:".json_encode(array($xmlConfig->widgets->js), JSON_UNESCAPED_UNICODE)."') %}";
        	}
        }
        // we apply init methods of the applyed service.
        if ( $xmlConfig->widgets->get('gedmo') && $xmlConfig->widgets->gedmo->get('controller') ) {
            $controller    = $xmlConfig->widgets->gedmo->controller;
            $values     = explode(':', $controller);
            $entity     = strtolower($values[1]);
            $method    .= strtolower($values[2]);
            $this->script['init'][$container.$NameAction.$method] =  "{% initWidget('". $container . ":" . $NameAction . $method ."') %}";
        }elseif ( $xmlConfig->widgets->get('content') && $xmlConfig->widgets->content->get('controller') ) {
            $controller    = $xmlConfig->widgets->content->controller;
            str_replace(':', ':', $controller, $count);
            if ($count == 1) {
                $this->script['init'][$container.$NameAction.$method] =  "{% initWidget('". $container . ":" . $NameAction . ":" . $controller ."') %}";
            }
        }elseif ( $xmlConfig->widgets->get('search') && $xmlConfig->widgets->search->get('controller') ) {
            $controller    = $xmlConfig->widgets->search->controller;
            str_replace(':', ':', $controller, $count);
            if ($count == 1) {
                $this->script['init'][$container.$NameAction.$method] =  "{% initWidget('". $container . ":" . $NameAction . ":" . $controller ."') %}";
            }
        } else {
            $this->script['init'][$container.$NameAction.$method] =  "{% initWidget('". $container . ":" . $NameAction . $method ."') %}";
        }
    }    
    
    /**
     * Sets widget translations.
     *
     * @param Widget $widget A widget entity
     *
     * @return array|TranslationWidget
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-13
     */
    protected function setWidgetTranslations(Widget $widget)
    {
        $all_translations = $widget->getTranslations();
                
        $this->translationsWidget[$widget->getId()] = null;
        if ($all_translations instanceof \Doctrine\ORM\PersistentCollection){
            // records all translations
            foreach ($all_translations as $translation) {
                $this->translationsWidget[$widget->getId()][$translation->getLangCode()->getId()] = $translation;
            }
        }        
        return $this->translationsWidget[$widget->getId()];
    }    
    
    /**
     * Sets the response to one widget.
     * 
     * @param Widget   $widget   A widget entity
     * @param Response $response The response instance
     *
     * @return void
     * @access private
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-31
     */
    private function setResponse($widget, Response $response)
    {
        $this->responses['widget'][$widget->getId()] = $response;
    }        
}
