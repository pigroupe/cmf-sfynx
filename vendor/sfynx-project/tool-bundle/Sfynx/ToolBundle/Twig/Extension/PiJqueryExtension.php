<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage   Tool
 * @package    Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Sfynx\ToolBundle\Twig\TokenParser\StyleSheetJqueryTokenParser;
use Sfynx\ToolBundle\Exception\ServiceException;

/**
 * Jquery Matrix used in twig
 *
 * @subpackage   Tool
 * @package    Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiJqueryExtension extends \Twig_Extension
{
    /**
     * Content de rendu du script.
     *
     * @static
     * @var int
     * @access  private
     */
    protected static $_content;    
    
    /**
     * @var string service name
     */
    private $service;
        
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    
    /**
     * @var \Symfony\Component\Locale\Locale
     */
    protected $locale;    
    
    /**
     * @var \Symfony\Component\Translation\Translator
     */
    protected $translator;    
    
    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container      = $container;
        $this->locale         = $this->container->get('request')->getLocale();
        $this->translator     = $this->container->get('translator');
    }    
    
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     * @access public
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final public function getName()
    {
        return 'sfynx_tool_jquery_extension';
    }
        
    /**
     * Returns a list of functions to add to the existing list.
     *
     * <code>
     *  {% set options = {'prototype-name': ['translations'], 'prototype-tab-title': 'Translate'} %}
     *  {{ renderJquery('FORM', 'prototype-bytab', options )|raw }}
     * </code>
     *
     * @return array An array of functions
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final public function getFunctions()
    {
        return array(
                'renderJquery'  => new \Twig_Function_Method($this, 'FactoryFunction'),
        );
    }
    
    /**
     * Returns the token parsers
     *
     * <code>
     *     {%  initJquery 'FORM:prototype-bytab' %} to execute the init method of the service
     * </code>
     *
     * @return string The extension name
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final public function getTokenParsers()
    {
        return array(
                new StyleSheetJqueryTokenParser($this->getName()),
        );
    }    
    
    /**
     * Callbacks
     */
    
    /**
     * Factory ! We check that the requested class is a valid service.
     *
     * @static
     * @param  string         $container            name of jquery container.
     * @param  string         $NameClassValidator    name of validator.
     * @param  array        $options            validator options.
     * @return service
     * @access public
     * 
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    final public function FactoryFunction($container, $NameServiceValidator, $options = null)
    {
        if ($this->isServiceSupported($container, $NameServiceValidator))
            return  $this->container->get($this->service)->run($options);
    } 
    
    /**
     * execute the jquery service init method.
     *
     * @static
     * @param  string         $InfoService    service information ex : "contenaireName:NameServiceValidator"
     * @return void
     * @access public
     * 
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    final public function initJquery($InfoService)
    {
        $infos = explode(":", $InfoService);
        if (count($infos) <=1) {
            throw ServiceException::serviceParameterUndefined($InfoService);
        }
        if (count($infos) ==2) {
            $container                 = $infos[0];
            $NameServiceValidator    = $infos[1];
            $options                = null;
        } elseif (count($infos) == 3) {
            $container                 = $infos[0];
            $NameServiceValidator    = $infos[1];
            $options                = $infos[2];
        }        
        if ($this->isServiceSupported($container, $NameServiceValidator)) {
            $this->container->get($this->service)->init($options);
        }
    }  

    /**
     * Gets the service name.
     *
     * @static
     * @param  string         $container                name of jquery container.
     * @param  string         $NameServiceValidator    name of validator.
     *
     * @return boolean
     * @access public
     *
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    private function isServiceSupported($container, $NameServiceValidator)
    {
        if (!isset($GLOBALS['JQUERY'][strtoupper($container)][strtolower($NameServiceValidator)]))
            throw ServiceException::serviceGlobaleUndefined(strtolower($NameServiceValidator), 'JQUERY', __CLASS__);
        elseif (!$this->container->has($GLOBALS['JQUERY'][strtoupper($container)][strtolower($NameServiceValidator)]))
            throw ServiceException::serviceNotSupported($GLOBALS['JQUERY'][strtoupper($container)][strtolower($NameServiceValidator)]);
        else
            $this->service = $GLOBALS['JQUERY'][strtoupper($container)][strtolower($NameServiceValidator)];
        
        return true;
    }    

    /**
     * Call the render function of the child class called by service.
     *
     * @return string
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    final public function run($options = null)
    {
        return $this->render($options);
        
        try{
            return $this->render($options);
        } catch (\Exception $e) {
            throw ServiceException::serviceRenderUndefined('JQUERY');
        }
    }
    protected function render($options = null) {}  
    
    /**
     * Return a JS file in the container in links.
     *
     * @param    $content_js string    content js value
     * @param    $content_html string    content html value
     * @param    $path_prefix string    prefix repository value
     * @return string
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function renderScript($content_js, $content_html, $path_prefix = '/', $result = "both")
    {
        return $this->container->get('sfynx.tool.script_manager')->renderScript($content_js, $content_html, $path_prefix, $result);
    }    

}