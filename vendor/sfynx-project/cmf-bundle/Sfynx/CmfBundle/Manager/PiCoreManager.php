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

use Symfony\Component\Locale\Locale;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

use Sfynx\CmfBundle\Builder\PiCoreManagerBuilderInterface;
use Sfynx\ToolBundle\Util\PiFileManager;
use Sfynx\CmfBundle\Entity\Page;
use Sfynx\CmfBundle\Entity\TranslationPage;
use Sfynx\CmfBundle\Entity\Widget;
use Sfynx\CmfBundle\Entity\TranslationWidget;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

/**
 * Description of the Page manager
 *
 * @subpackage   Admin_Managers
 * @package    Manager
 * @abstract
 * 
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class PiCoreManager implements PiCoreManagerBuilderInterface 
{  
    /**
     * @var array
     * @static
     */
    static $types = array('page', 'widget', 'transwidget', 'listener', 'navigation', 'organigram', 'slider', 'jqext', 'lucene');
    
    /**
     * @var array
     * @static
     */
    static $global_blocks = array('global_script_js', 'global_script_css', 'global_script_divers', 'global_title', 'global_meta', 'title', 'global_layout', 'global_flashes');
    
    /**
     * @var array
     * @static
     */
    static $scriptType = array('js', 'css', 'init');    

    /**
     * @var array
     */
    protected $script = array();    
        
    /**
     * @var array of \Sfynx\CmfBundle\Entity\Page
     */
    protected $pages         = array();
    
    /**
     * @var \Sfynx\CmfBundle\Entity\TransaltionPage
     */
    protected $translations;
    
    /**
     * @var \Sfynx\CmfBundle\Entity\TranslationWidget
     */
    protected $translationsWidget;    
    
    /**
     * @var array of \Sfynx\CmfBundle\Entity\Block
     */
    protected $blocks         = array();
    
    /**
     * @var array of \Sfynx\CmfBundle\Entity\Widget
     */
    protected $widgets         = array();
    
    /**
     * @var array of \Symfony\Component\HttpFoundation\Response
     */
    protected $responses;    
    
    /**
     * @var \Sfynx\CmfBundle\Entity\Page
     */
    protected $currentPage;
    
    /**
     * @var \Sfynx\CmfBundle\Entity\Widget
     */    
    protected $currentWidget;
    
    /**
     * @var \Sfynx\CmfBundle\Entity\TranslationWidget
     */    
    protected $currentTransWidget;
    
    /**
     * @var \Sfynx\CmfBundle\Twig\Extension\PiWidgetExtension
     */    
    protected $extensionWidget;
    
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RepositoryBuilderInterface
     */    
    protected $repository;
    
    /**
     * @var \Symfony\Component\Locale\Locale 
     */    
    protected $language;
    
    /**
     * @var string
     */
    protected $Etag = "";    
    
    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container       = $container;
        $this->language        = $this->container->get('request')->getLocale();
        $this->extensionWidget = $this->container->get('pi_app_admin.twig.extension.widget');
        //
        $this->script['js']    = array();
        $this->script['css']   = array();
        $this->script['init']  = array();
    }
    
    /**
     * Create the Etag and returns the render source it.
     *
     * @param string $tag
     * @param string $id
     * @param string $lang
     * @param array  $params
     *
     * @return string translation widget content
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2012-04-19
     */
    public function run($tag, $id, $lang, $params = null, $isCreateJsonFile = false)
    {
        // we create the tag value
        $this->createEtag($tag, $id, $lang, $params);
        // we register the tag value in the json file if does not exist.
        if ($isCreateJsonFile) {
            $this->setJsonFileEtag($tag, $id, $lang, $params);
        }
        //print_r($this->Etag);
        
        // we return the render (cache or not)
        return $this->render($lang);
    }

    /**
     * Cretae a Etag.
     *
     * @param string $tag    Tag value
     * @param string $id     Id value
     * @param string $lang   Lang value
     * @param array  $params Params value
     *
     * @return string Etag value
     * @access protected
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2012-04-19
     */
    protected function createEtag($tag, $id, $lang, $params = null)
    {
    	// We cretae and set the Etag value
    	if (!is_null($params)) {
            // we sort an array by key in reverse order
            $this->container->get('sfynx.tool.array_manager')->recursive_method($params, 'krsort');
            $params = $this->paramsEncode($params);
            $id     = $this->_Encode($id, false);
            $this->setEtag("$tag:$id:$lang:$params");
    	} else {
            $id     = $this->_Encode($id, false);
            $this->setEtag("$tag:$id:$lang");
    	}
    
    	return $this->Etag;
    }    
    
    /**
     * Create the json path name
     *
     * @param string $type Type value
     * @param string $id   id value 
     * @param string $lang lang value
     * 
     * @return string   path value
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2014-04-03
     */    
    public function createJsonFileName($type, $id, $lang = '') 
    {
        // we set the path
        $path  = $this->container->getParameter("pi_app_admin.cache_dir.etag");
        if ($type == 'page-json') {
            $path_json_file = $path . "page-json-entity/p-{$id}.json";
        } elseif ($type == 'esi') {
            $path_json_file = $path . "esi/etag-{$id}-{$lang}.json";
        } elseif ($type == 'esi-tmp') {
            $path_json_file = $path . "esi/tmp/" . md5($id) ."-{$lang}.json";
        } elseif ($type == 'page') {
            $path_json_file = $path . "page/p-{$id}-{$lang}.json";
        } elseif ($type == 'page-sluggify') {
            $path_json_file = $path . "page/s-{$id}-{$lang}-sluggify.json";
        } elseif ($type == 'page-sluggify-tmp') {
            $path_json_file = $path . "page/tmp/s-" . md5($id) ."-{$lang}.json";
        } elseif ($type == 'page-history') {
            $path_json_file = $path . "page/h-{$id}-{$lang}-history.json";
        } elseif ($type == 'page-history-tmp') {
            $path_json_file = $path . "page/tmp/h-" . md5($id) ."-{$lang}.json";
        } elseif ($type == 'widget') {
            $path_json_file = $path . "widget/w-{$id}-{$lang}.json";
        } elseif ($type == 'widget-history') {
            $path_json_file = $path . "widget/h-{$id}-{$lang}-history.json";
        } elseif ($type == 'widget-history-tmp') {
            $path_json_file = $path . "widget/tmp/" . md5($id) ."-{$lang}.json";
        } elseif ($type == 'default') {
            $path_json_file = $path . "etag-{$id}-{$lang}.json";
        } elseif ($type == 'default-tmp') {
            $path_json_file = $path . "tmp/" . md5($id) ."-{$lang}.json";
        } else {
            throw new \InvalidArgumentException("you have to config correctely the attibute type");
        }
        
        return $path_json_file;
    }
    
    /**
     * Create/update json file Etag with the tag value.
     *
     * @param string $tag
     * @param string $id
     * @param string $lang
     * @param array  $params
     * 
     * @return boolean true if the tag have been insert corectly in the json file.
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2014-04-03
     */
    public function setJsonFileEtag($tag, $id, $lang, $params = null)
    {
        $result = false;
        // we set the time
        $now    = $this->setTimestampNow();
    	// we set the Etag.
        $this->createEtag($tag, $id, $lang, $params);
    	// we set the path
    	$path   = $this->container->getParameter("pi_app_admin.cache_dir.etag");
    	// we set the file name
    	if ( isset($params['page-url']) && !empty($params['page-url']) && ($tag == "page") ) {
            // if the page is sluggify    		
            if ($this->isSluggifyPage()) {    		
                $path_json_file_tmp = $this->createJsonFileName('page-sluggify-tmp', $this->Etag, $lang);
                if (!file_exists($path_json_file_tmp)) {
                    $result = PiFileManager::save($path_json_file_tmp, $now.'|'.$this->Etag.'|'.$params['page-url']."\n", 0777, LOCK_EX);
                    // we add new Etag in the sluggify file.
                    $path_json_file_sluggify = $this->createJsonFileName('page-sluggify', $id, $lang);
                    $result = PiFileManager::save($path_json_file_sluggify, $now.'|'.$this->Etag.'|'.$params['page-url']."\n", 0777, FILE_APPEND);
                }
            // if the page has queries
            } elseif ($this->isQueryStringPage()) {	
                $path_json_file_tmp = $this->createJsonFileName('page-history-tmp', $this->Etag, $lang);
                if (!file_exists($path_json_file_tmp)) {
                    $result = PiFileManager::save($path_json_file_tmp, $now.'|'.$this->Etag.'|'.$params['page-url']."\n", 0777, LOCK_EX);
                    // we add new Etag in the history.
                    $path_json_file_history = $this->createJsonFileName('page-history', $id, $lang);
                    $result = PiFileManager::save($path_json_file_history, $now.'|'.$this->Etag.'|'.$params['page-url']."\n", 0777, FILE_APPEND);    		    
                }
            } else {
                $path_json_file   = $this->createJsonFileName('page', $id, $lang);
                if (!file_exists($path_json_file)) {
                    $result = PiFileManager::save($path_json_file, $now.'|'.$this->Etag."\n", 0777, LOCK_EX);
                }
            }
    	} elseif ( isset($params['esi-url']) && !empty($params['esi-url']) && ($tag == "esi") ) {
    	    $path_json_file_tmp = $this->createJsonFileName('esi-tmp', $params['esi-url'], $lang);
            if (!file_exists($path_json_file_tmp)) {
                $result = PiFileManager::save($path_json_file_tmp, $now.'|'.$params['esi-url']."\n", 0777, LOCK_EX);
                // we add new ESI tag in the file.
                $path_json_file = $this->createJsonFileName('esi', $id, $lang);
                $result = PiFileManager::save($path_json_file, $now.'|'.$params['esi-url']."\n", 0777, FILE_APPEND);
            }
    	} elseif (isset($params['widget-id']) && !empty($params['widget-id'])) {
    	    if (isset($params['widget-sluggify-url'])) {
    	        $path_json_file_tmp = $this->createJsonFileName('widget-history-tmp', $this->Etag, $lang);
    	        if (!file_exists($path_json_file_tmp)) {
                    $result = PiFileManager::save($path_json_file_tmp, $now.'|'.$this->Etag."\n", 0777, LOCK_EX);
                    // we add new Etag in the history.
                    $path_json_file_history = $this->createJsonFileName('widget-history', $params['widget-id'], $lang);
                    $result = PiFileManager::save($path_json_file_history, $now.'|'.$this->Etag."\n", 0777, FILE_APPEND);
    	        }
    	    } else {
                $path_json_file = $this->createJsonFileName('widget', $params['widget-id'], $lang);
                $result = PiFileManager::save($path_json_file, $now.'|'.$this->Etag."\n", 0777, LOCK_EX);
    	    }
    	} else {
            $path_json_file_tmp = $this->createJsonFileName('default-tmp', $this->Etag, $lang);
            if (!file_exists($path_json_file_tmp)) {
                $result = PiFileManager::save($path_json_file_tmp, $now.'|'.$this->Etag."\n", 0777, LOCK_EX);
                $path_json_file = $this->createJsonFileName('default', $tag, $lang);
                $result = PiFileManager::save($path_json_file, $now.'|'.$this->Etag."\n", 0777, FILE_APPEND);
            }
    	}
    
    	return $result;
    }   
    
    /**
     * Create the repository of the cache widget files
     *
     * @return string   path value
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2014-06-21
     */
    public function createCacheWidgetRepository()
    {
        $path  = $this->container->getParameter("pi_app_admin.cache_dir.widget");
    	PiFileManager::mkdirr($path, 0777);
    
    	return $path;
    }    

    /**
     * Refresh the cache by name
     *
     * @param string $name    the name of the cache file.
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-04-03
     */
    public function cacheRefreshByname($name, $onlyDelete = true)
    {
    	$name = str_replace('\\\\', '\\', $name);
    	// Delete the cache filename of the template.
    	try {
            $this->container->get('pi_app_admin.caching')->invalidate($name);
    	} catch (\Exception $e) {
    	}
    	// Loads and warms up a template by name.
    	try {
            if (!$onlyDelete) {
                $this->container->get('pi_app_admin.caching')->warmup($name);
            }
    	} catch (\Exception $e) {
    	}
    }    
    
    protected function setTimestampNow()
    {
    	$now = new \Datetime();
    	 
    	return $now->getTimestamp();
    }
    
    protected function paramsEncode($params)
    {
    	$string    = json_encode($params, JSON_NUMERIC_CHECK  | JSON_UNESCAPED_UNICODE);
        
    	return $this->_Encode($string);
    }
    
    protected function _Encode($string, $complet = true)
    {
    	$string = str_replace('\\\\', '\\', $string);
    	if ($complet) {
            $string = str_replace('\\', "@@", $string);
            $string = str_replace('@@@@@@@@', "@@", $string);
            $string = str_replace('@@@@', "@@", $string);
    	}
    
    	return str_replace(':', '#', $string);
    }
    
    protected function paramsDecode($params)
    {
    	$params = $this->_Decode($params);
    	$params = str_replace('\\', '\\\\', $params);
    	$params = json_decode($params, true);
    	if (is_array($params)){
            $this->container->get('sfynx.tool.array_manager')->recursive_method($params, 'krsort');
            $name_key = array_map(function($key, $value) {
                    return str_replace('\\\\', '\\', $value);
            }, array_keys($params),array_values($params));
            $params = array_combine(array_keys($params), $name_key);
    	}
    
    	return $params;
    }
    
    protected function _Decode($string)
    {
    	$string = str_replace("@@", '\\', $string);
    	$string = str_replace('\\\\', '\\', $string);
    	$string = str_replace('#', ':', $string);
    	$string = str_replace("$$$", "&", $string);
    
    	return $string;
    }
    
    protected function recursive_map(array &$array, $curlevel=0)
    {
    	foreach ($array as $k=>$v) {
            if (is_array($v)) {
                $this->recursive_map($v, $curlevel+1);
            } else {
                $v = str_replace("@@@@", '\\', $v);
                $v = str_replace("@@", '\\', $v);
                $v = str_replace('\\\\', '\\', $v);
                $v = str_replace("$$$", "&", $v);
                $array[$k] =  mb_convert_encoding($v, "UTF-8", "HTML-ENTITIES");
            }
    	}
    }    
    
    /**
     * Sets Etag
     *
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-03-20
     */
    protected function setEtag($Etag)
    {
        $this->Etag = $Etag;
    }    
    
    /**
     * Call the render method by default.
     *
     * @param string $lang
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2012-04-18
     */
    public function render($lang = '')
    {
        //     Initialize response
        $response = $this->getResponseByIdAndType('default', $this->Etag);        
        // Create a Response with a Last-Modified header.
        $response = $this->configureCache(null, $response);        
        // Check that the Response is not modified for the given Request
        if ($response->isNotModified($this->container->get('request'))){
            // We set the reponse
            $this->setResponse($this->Etag, $response);        
            // return the 304 Response immediately
            return $response;
        } else {
            // or render a template with the $response you've already started
            $response = $this->container->get('pi_app_admin.caching')->renderResponse($this->Etag, array(), $response);        
            // We set the reponse
            $this->setResponse($this->Etag, $response);        
            // we don't send the header but the content only.
            return $response->getContent();
        }        
    }
    
    /**
     * Call the render source method of the child class called by service.
     *
     * @param string $id
     * @param string $lang
     * @param array  $params
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since  2012-01-31
     */
    public function renderSource($id, $lang = '', $params = null){}
    
    /**
     * Configure the caching settings of the response
     * 
     * Responses with neither a freshness lifetime (Expires, max-age) nor cache
     * validator (Last-Modified, ETag) are considered uncacheable.
     *
     * @param object   $object
     * @param Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-05
     */
    protected function configureCache($object, Response $response)
    {
        if (empty($this->Etag)) {
            throw new \InvalidArgumentException("you have to config the attibute Etag");
        }
        if ( !method_exists($object, 'getPublic') && method_exists($object, 'getWidget') ) {
            $object = $object->getWidget();
        }
        // Allows proxies to cache the same content for different visitors.
        if (method_exists($object, 'getPublic') && $object->getPublic()) {
            $response->setPublic();
        }    
        if (method_exists($object, 'getLifetime') && $object->getLifetime()) {
            // server side caching
            $response->setSharedMaxAge($object->getLifetime());
            // Une fois que ESI est utilisée, il ne faut pas oublier de toujours utiliser la directive s-maxage à la place de max-age. 
            // Comme le navigateur ne reçoit que la réponse « agrégée » de la ressource, il n'est pas conscient de son « sous-contenu », 
            // il suit la directive max-age et met toute la page en cache. Et ce n'est pas ce que vous voulez.
            // we get instances of parser and dumper component yaml files.
            $isEsi = $this->container->getParameter('pi_app_admin.page.esi.authorized');
            if ($isEsi) {
            } else {
                // browser side caching
            	$response->setMaxAge($object->getLifetime());
            }
        } 
        // Returns a 304 "not modified" status, when the template has not changed since last visit.
        if (method_exists($object, 'getCacheable') &&  $object->getCacheable()) {
            $response->setLastModified($object->getUpdatedAt());
        } else {
            $response->setLastModified(new \DateTime());
        }    
        //
        if ( $this->isUsernamePasswordToken() ) {
            $response->headers->set('Pragma', "no-cache");
            $response->headers->set('Cache-control', "private");
        } elseif ( (method_exists($object, 'getLifetime') && ($object->getLifetime() == 0))  ) {
            // server side caching
            $response->setSharedMaxAge(0);
            // browser side caching
            $response->setMaxAge(0);
        }
        if (method_exists($object, 'getMetaContentType')) {
            $response->headers->set('Content-Type', $object->getMetaContentType());
        }
        
        return $response;
    }
    
    /**
     * Sets the response to one tree.
     *
     * @param strgin   $Etag
     * @param Response $response
     *
     * @return void
     * @access private
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-04-19
     */
    private function setResponse($Etag, Response $response)
    {
        $this->responses['default'][$Etag] = $response;
    }    
    
    /**
     * Gets the container instance.
     *
     * @return ContainerInterface
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getContainer()
    {
        return $this->container;
    }    
    
    /**
     * Returns the current page
     *
     * @return Page
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-01-23
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }
    
    /**
     * Sets the current page.
     * 
     * @param null|Page $page
     *
     * @return void
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-23
     */
    public function setCurrentPage(Page $page = null)
    {
        $this->currentPage = $page;
    }

    /**
     * Returns the current Widget
     *
     * @param int $id id widget
     * 
     * @return Widget
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-31
     */
    public function getCurrentWidget()
    {
        return $this->currentWidget;
    }
    
    /**
     * Sets the current Widget.
     *
     * @param null|Widget $widget
     *
     * @return void
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-31
     */
    public function setCurrentWidget(Widget $widget = null)
    {
        $this->currentWidget = $widget;        
        // we set the widget.
        $this->setWidgetTranslations($widget);
    }    
    
    /**
     * Returns the current Widget
     *
     * @return TranslationWidget
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-15
     */
    public function getCurrentTransWidget()
    {
        return $this->currentTransWidget;
    }
    
    /**
     * Sets the current Widget.
     *
     * @param null|TranslationWidget $transWidget
     *
     * @return void
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-15
     */
    public function setCurrentTransWidget(TranslationWidget $transWidget = null)
    {
        $this->currentTransWidget = $transWidget;
    }    
    
    /**
     * Sets widget translations.
     *
     * @param Widget $widget
     *
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-13
     */
    protected function setWidgetTranslations(Widget $widgets){}
    
    /**
     * Returns the page with this id.
     *
     * @param int     $idpage  id page
     * @param boolean $isForce True to force setting page 
     * 
     * @return Page
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-31
     */
    public function getPageById($idpage, $isForce = false)
    {
        if (isset($this->pages[$idpage]) && !empty($this->pages[$idpage])) {
            return $this->pages[$idpage];
        } elseif ($isForce) {
            $page = $this->getRepository('Page')->findOneById($idpage);
            $this->setCurrentPage($page);
            return $page;
        } else {
            return false;
        }
    }    
    
    /**
     * Returns the page with this id.
     *
     * @param string  $route   Route page value
     * @param boolean $isForce True to force setting page 
     * 
     * @return Page
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-31
     */
    public function getPageByRoute($route, $isForce = false)
    {
        $page = false;
        if ($path_page_json_file = $this->isJsonPageFileExisted($route)) {
            $report = file_get_contents($path_page_json_file); 
            $page = unserialize($report);  
        } else {
            $page = $this->getRepository('Page')->getPageByRoute($route);
            $this->cachePage($page, "persist");
        } 
        if ($isForce 
                && ($page instanceof Page)
        ) {
            $this->setCurrentPage($page);
        }
        
        return $page;       
    }  
    
    protected function cachePage($entity, $type){}
    
    /**
     * Returns the blocks of a page.
     *
     * @param int $idpage id page
     * 
     * @return array of \Sfynx\CmfBundle\Entity\Block
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-23
     */
    public function getBlocksByPageId($idpage)
    {
        if (isset($this->blocks[$idpage]) && !empty($this->blocks[$idpage])) {
            return $this->blocks[$idpage];
        } else {
            return false;
        }
    }
    
    /**
     * Returns the widget with this id.
     *
     * @param int $idWidget id widget
     *
     * @return Widget
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-31
     */
    public function getWidgetById($idWidget)
    {
        if (isset($this->widgets[$idWidget]) && !empty($this->widgets[$idWidget])) {
            return $this->widgets[$idWidget];
        } else {
            $widget = $this->getRepository('Widget')->findOneById($idWidget);
            $this->setCurrentWidget($widget);
            return $widget;
        }
    }    
    
    /**
     * Returns the block with this id.
     *
     * @param int $id id block
     *
     * @return Block
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-05-09
     */
    public function getBlockById($idBlock)
    {
        return $this->getRepository('Block')->findOneById($idBlock);
    }    
    
    /**
     * Returns the translation of a page.
     *
     * @param int    $idpage id page
     * @param string $lang   lang value
     * 
     * @return TranslationPage
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-23
     */
    public function getTranslationByPageId($idpage, $lang = '')
    {
        if (isset($this->translations[$idpage]) && !empty($this->translations[$idpage])) {
            if (!empty($lang) 
                    && isset($this->translations[$idpage][$lang]) 
                    && !empty($this->translations[$idpage][$lang]) 
            ) {
                $result         = $this->translations[$idpage][$lang];
                $this->language = $lang;
            } elseif (!empty($this->language) 
                    && isset($this->translations[$idpage][$this->language]) 
                    && !empty($this->translations[$idpage][$this->language]) 
            ) {
                $result         = $this->translations[$idpage][$this->language];
            } else {
                $result         =  end($this->translations[$idpage]);
                if ($result instanceof TranslationPage) {
                    $this->language = $result->getLangCode()->getId();
                } else { 
                    $result = false;
                }
            }
        } else {
            $result = false;
        }
        
        return $result;
    }
    
    /**
     * Returns the translation of a widget.
     *
     * @param int    $idwidget id widget
     * @param string $lang     lang vlue
     *
     * @return TranslationWidget
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-13
     */
    public function getTranslationByWidgetId($idwidget, $lang = '')
    {
        // we set the langue
        if (empty($lang)) {
            $lang = $this->language;
        }        
        if (isset($this->translationsWidget[$idwidget]) && !empty($this->translationsWidget[$idwidget])){
            if (!empty($lang) 
                    && isset($this->translationsWidget[$idwidget][$lang]) 
                    && !empty($this->translationsWidget[$idwidget][$lang]) 
            ) {
                $result = $this->translationsWidget[$idwidget][$lang];
            } elseif (!empty($this->language) 
            		&& isset($this->translationsWidget[$idwidget][$this->language]) 
            		&& !empty($this->translationsWidget[$idwidget][$this->language]) 
            ) {
                $result = $this->translationsWidget[$idwidget][$this->language];
            } else {
                $result =  $this->translationsWidget[$idwidget];
            }
        } else {
            $result = $this->getRepository('TranslationWidget')->getTranslationById($idwidget, $lang);
        }        
        // we secure if the result is an array of translation object.
        if (is_array($result)) {
            $result = end($result);
        }        
        // Initialize Locale
        if ($result instanceof TranslationWidget){
            $this->language = $result->getLangCode()->getId();
            // we set the result
            $this->setCurrentTransWidget($result);
        }        
        
        return $result;
    }    

    /**
     * Returns the response given in param.
     *
     * @param string  $type values = ['layout', 'page', 'widget']
     * @param integer $id   id of the type entity given in param
     * 
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-31
     */
    public function getResponseByIdAndType($type, $id)
    {
        if (isset($this->responses[$type][$id]) && !empty($this->responses[$type][$id])) {
            return $this->responses[$type][$id];
        } else {
            return new Response();
        }
    }    
    
    /**
     * Returns the params given in the render response of the service template.
     *
     * @param string $RenderResponseParam
     * 
     * @return array
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-31
     */    
    public function parseTemplateParam($RenderResponseParam)
    {
        $name_parts = explode(':', $RenderResponseParam);
        if (count($name_parts) < 2) {
            return false;
        }    
        $type = $name_parts[0];
        if (!in_array($type, self::$types)) {
            return false;
        }    
        $idPage = $name_parts[1];
        $lang    = $name_parts[2];
        if (isset($name_parts[3]) && !empty($name_parts[3])) {
            $params = $name_parts[3];
        } else {
            $params = null;
        }
    
        return array($type, $idPage, $lang, $params);
    }

    /**
     * Returns the script due to the type. Return false if script argument is empty or script param doesn't exist.
     *
     * @param string $script The script content
     * @param string $type   = ['array', 'implode', 'collection']
     * 
     * @return array
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-02-16
     */
    public function getScript($script, $type = 'string')
    {
        if (!in_array($script, self::$scriptType)) {
            return false;
        }
        if ($type == "implode") {
            return implode("\n", array_unique($this->script[$script]));
        } elseif ($type == "array") {
            return array_unique($this->script[$script]);
        } else {
            return false;
        }
    }    
        
    /**
     * Sets the repository service.
     * 
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-23
     */
    protected function setRepository()
    {
        $this->repository = $this->container->get('pi_app_admin.repository');
    }
    
    /**
     * Gets the repository service of the entity given in param.
     * 
     * @return ObjectRepository
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-01-23
     */
    protected function getRepository($nameEntity = '')
    {
        if (empty($this->repository)) {
            $this->setRepository();
        }
        if (!empty($nameEntity)) {
            return $this->repository->getRepository($nameEntity);
        } else {
            throw new \Doctrine\ORM\EntityNotFoundException();
        }
    }
    
    /**
     * Return the token object.
     *
     * @return object Token class
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function getToken()
    {
        return  $this->container->get('security.context')->getToken();
    }
    
    /**
     * Return treu if the json page file existed
     * 
     * @param string $route Route page value
     * 
     * @return boolean
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isJsonPageFileExisted($route)
    {
        $path_page_json_file = $this->createJsonFileName('page-json', $route); 
        if (file_exists($path_page_json_file)) {
            return $path_page_json_file;
        }
        
        return false;
    }      

    /**
     * Return if yes or no the user is anonymous token.
     *
     * @return boolean
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isAnonymousToken()
    {
        if (($this->getToken() instanceof AnonymousToken)
            || ($this->getToken() === null)
        ) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Return if yes or no the user is UsernamePassword token.
     *
     * @return boolean
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isUsernamePasswordToken()
    {
        if ($this->getToken() instanceof UsernamePasswordToken) {
            return true;
        } else {
            return false;
        }
    }    
    
    /**
     * Return the user roles.
     *
     * @return array user roles
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function getUserRoles()
    {
        return $this->getToken()->getUser()->getRoles();
    }    
    
    /**
     * Return if yes or no the widget given in param is supported.
     *
     * @param Widget $widget A Widget entity
     * 
     * @return boolean
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function isWidgetSupported(Widget $widget)
    {
        if (isset($GLOBALS['WIDGET'][strtoupper($widget->getPlugin())][strtolower($widget->getAction())]) ) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Sets the flash message.
     *
     * @param string $message Message value
     * @param string $type    Type value
     *
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function setFlash($message, $type = "notice")
    {
        $this->container->get('request')->getSession()->getFlashBag()->add($type, $message);
    }

    /**
     * Return the meta info of a page.
     * 
     * <code>
     * $GLOBALS['ROUTE']['SLUGGABLE']['my_page_route_name'] = array(
     *     'entity'         => 'PiAppGedmoBundle:Article',
     *     'field_name'     => 'slug'
     *     'field_search'   => 'slug',   // if like 'slug_id'  then add 'delimiter' config
     *     //'delimiter'      => '_'
     *     'field_title'    => 'title',
     *     'field_resume'   => 'meta_description',
     *     'field_keywords' => 'meta_keywords',
     * );     
     * </code>
     *
     * @param string $lang        Lang value
     * @param string $title       Title value
     * @param string $description Desc value
     * @param string $keywords    Keuword value
     * @param string $pathinfo    Path value
     * 
     * @return array
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2014-04-03
     */
    public function getPageMetaInfo($lang = '', $title = '', $description = '', $keywords = '', $pathInfo = "")
    {
    	// we set values.
    	$options['title']       = str_replace(array('"',"’"), array("'","'"), strip_tags($this->container->get('translator')->trans($title)));
    	$options['description'] = str_replace(array('"',"’"), array("'","'"), strip_tags($this->container->get('translator')->trans($description)));
    	$options['keywords']    = str_replace(array('"',"’"), array("'","'"), strip_tags($this->container->get('translator')->trans($keywords)));
    	// we set sluggify values.
    	try {
            if (empty($lang)) {
                $lang     = $this->container->get('request')->getLocale();
            }
            if (empty($pathInfo)) {
                $pathInfo = $this->container->get('request')->getPathInfo();
            }
            $match  = $this->container->get('be_simple_i18n_routing.router')->match($pathInfo);
            $route  = $match['_route'];
            $em     = $this->container->get('doctrine')->getManager();
            if (isset($GLOBALS['ROUTE']['SLUGGABLE'][ $route ]) 
                    && !empty($GLOBALS['ROUTE']['SLUGGABLE'][ $route ])
            ) {
                $sluggable_entity       = $GLOBALS['ROUTE']['SLUGGABLE'][ $route ]['entity'];
                $sluggable_field_search = $GLOBALS['ROUTE']['SLUGGABLE'][ $route ]['field_search'];
                $sluggable_title        = $GLOBALS['ROUTE']['SLUGGABLE'][ $route ]['field_title'];
                $sluggable_resume       = $GLOBALS['ROUTE']['SLUGGABLE'][ $route ]['field_resume'];
                $sluggable_keywords     = $GLOBALS['ROUTE']['SLUGGABLE'][ $route ]['field_keywords'];
                //
                if (isset($GLOBALS['ROUTE']['SLUGGABLE'][ $route ]['field_name']) 
                        && !empty($GLOBALS['ROUTE']['SLUGGABLE'][ $route ]['field_name'])
                ) {
                    $sluggable_field_name = $GLOBALS['ROUTE']['SLUGGABLE'][ $route ]['field_name'];
                } else {
                    $sluggable_field_name =   $sluggable_field_search;
                }
                //
                if (!empty($GLOBALS['ROUTE']['SLUGGABLE'][ $route ]['delimiter'])) {
                    $delimiter = $GLOBALS['ROUTE']['SLUGGABLE'][ $route ]['delimiter'];
                    $composer  = explode('_', $sluggable_field_search);
                    //$output = preg_split( "/ (_|-) /", $input );
                    $trans_content = '';
                    $i=0;
                    foreach($composer as $id) {
                        if($i != 0) {
                            $trans_content  .= $delimiter . $match[$id];
                        } else  {
                            $trans_content  .= $match[$id];
                        }
                        $i++;
                    }
                } else {
                    $trans_content =   $match[$sluggable_field_search];
                }
                //                
                $sluggable_title_tab = array_map(function($value) {
                    return ucwords($value);
                }, array_values(explode('_', $sluggable_title)));
                $sluggable_resume_tab = array_map(function($value) {
                    return ucwords($value);
                }, array_values(explode('_', $sluggable_resume)));
                $sluggable_keywords_tab = array_map(function($value) {
                    return ucwords($value);
                }, array_values(explode('_', $sluggable_keywords)));
                //
                $method_title    = "get".implode('', $sluggable_title_tab);
                $method_resume   = "get".implode('', $sluggable_resume_tab);
                $method_keywords = "get".implode('', $sluggable_keywords_tab);
                //
                $query = $em->getRepository($sluggable_entity)
                    ->createQueryBuilder('a')
                    ->select("a")
                    ->leftJoin('a.translations', 'trans')
                    ->where("( trans.locale = :trans_locale AND trans.field = :trans_field AND trans.content = :trans_content)")
                    ->groupBy("a.id")
                    ->setParameters(array(
                        'trans_locale'  => $lang,
                        'trans_field'   => $sluggable_field_name,
                        'trans_content' => $trans_content
                    ));
                $entity = $query->getQuery()->getOneOrNullResult();
                if (!is_object($entity)) {
                    $query = $em->getRepository($sluggable_entity)
                    ->createQueryBuilder('a')
                    ->select("a")
                    ->where("a.{$sluggable_field_name} = :field_name")
                    ->groupBy("a.id")
                    ->setParameters(array(
                                    'field_name'    => $trans_content
                    ));
                    $entity = $query->getQuery()->getOneOrNullResult();
                }
                //    			
                if (is_object($entity)) {
                    $entity->setTranslatableLocale($lang);
                    $em->refresh($entity);
                    //
                    $title       = str_replace(array('"',"’"), array("'","'"), strip_tags($this->container->get('translator')->trans($entity->$method_title())));
                    $description = str_replace(array('"',"’"), array("'","'"), strip_tags($this->container->get('translator')->trans($entity->$method_resume())));
                    $keywords    = str_replace(array('"',"’"), array("'","'"), strip_tags($this->container->get('translator')->trans($entity->$method_keywords())));
                    if (!empty($title)) {
                        $options['title'] = $title;
                    }
                    if (!empty($description)) {
                        $options['description'] = $description;
                    }
                    if (!empty($keywords)) {
                        $options['keywords'] = $keywords;
                    }
                    $options['entity'] = $entity;
                } else {
                    // it allow to return a 404 exception.
                    $options['title'] = '_error_404_';
                }   
            }
        } catch (\Exception $e) {
            // it allow to return a 404 exception.
            $options['title'] = '_error_404_';
        }

        return $options;
    }   

    /**
     * Return true if the page is sluggify.
     *
     * @param string $pathinfo Path value
     * 
     * @return array
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2014-04-03
     */	
    public function isSluggifyPage($pathInfo = "") 
    {
        if (empty($pathInfo)) {
            $pathInfo = $this->container->get('request')->getPathInfo();
        }
        $match = $this->container
                ->get('be_simple_i18n_routing.router')
                ->match($pathInfo);
        $route = $match['_route'];
        $em    = $this->container->get('doctrine')->getManager();
        if (isset($GLOBALS['ROUTE']['SLUGGABLE'][ $route ]) 
                && !empty($GLOBALS['ROUTE']['SLUGGABLE'][ $route ])
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return true if the page has a query string.
     *
     * @return boolean
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2014-04-03
     */
    public function isQueryStringPage()
    {
        // we get query string
        if (null !== $qs = $this->container->get('request')->getQueryString()) {
            return true;
        } else {
            return false;
        }
    }	
}
