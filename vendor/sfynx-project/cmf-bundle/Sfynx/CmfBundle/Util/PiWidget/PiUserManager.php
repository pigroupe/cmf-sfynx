<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin
 * @package    Widget
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-12-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Util\PiWidget;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

use Sfynx\CmfBundle\Twig\Extension\PiWidgetExtension;
use Sfynx\ToolBundle\Exception\ExtensionException;

/**
 * User Widget plugin
 *
 * @subpackage   Admin
 * @package    Widget
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiUserManager extends PiWidgetExtension
{
    /**
     * Constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface
     * @param string    action name
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function __construct(ContainerInterface $container, $action)
    {
        parent::__construct($container, 'USER', $action);
    }
    
    /**
     * Return list of available jqext.
     *
     * @return array
     * @access public
     * @static
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-07-23
     */
    public static function getAvailableConnexion()
    {
        return array(
                'SfynxAuthBundle:User'    => array(
                            'method' => array('_connexion_default','_reset_default'),
                ),    
        );
    }    
    
    /**
     * checks if the controller  and the action are in the container.
     *
     * @param string    $controller
     * @access protected
     * @return BooleanType
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-03-11
     */        
    protected function isAvailableAction($controller)
    {
           $values     = explode(':', $controller);
           $entity     = $values[0] .":". $values[1];
           $method     = strtolower($values[2]);
           //
           $getAvailable  = "getAvailable" . ucfirst($this->action);
           $Lists         = self::$getAvailable();
           //
           if ( $entity && !isset($Lists[$entity]) ) {
               return false;
           } elseif ( $entity && !in_array($method, $Lists[$entity]['method']) ) {
               return false;
           }
           $this->entity = $entity;
           $this->setMethod($method);
                          
           return true;
    }

    /**
     * Sets init.
     *
     * @access protected
     * @return void
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function init()
    {
    }

    /**
     * Sets the render of the connexion action.
     *
     * <code>
     *   <?xml version="1.0"?>
     *   <config>
     *         <widgets>
     *             <user>
     *                 <controller>SfynxAuthBundle:User:_connexion_default</controller>
     *                 <params>
     *                     <template>SfynxSmoothnessBundle:Login\\Security:connexion-ajax.html.twig</template>
     *                     <referer_redirection>true</referer_redirection>
     *                 </params>
     *             </user>
     *         </widgets>
     *   </config>
     *  </code>
     * 
     * <code>  
     *  {% set widget_service_params = {"template":"SfynxSmoothnessBundle:Login@@@@@@@@Security:connexion-ajax.html.twig"} %} 
     *  {{ getService('pi_app_admin.manager.authentication').renderSource('SfynxAuthBundle:User~_connexion_default', 'fr_FR', widget_service_params)|raw }}
     * </code>
     *  
     * <code>
     *   <?xml version="1.0"?>
     *   <config>
     *         <widgets>
     *             <user>
     *                 <controller>SfynxAuthBundle:User:_reset_default</controller>
     *                 <params>
     *                     <template>SfynxSmoothnessBundle:Login\\Resetting:reset_content.html.twig</template>
     *                     <path_url_redirection>page_route_name_reset</url_redirection>
     *                 </params>
     *             </user>
     *         </widgets>
     *   </config>
     *  </code>
     *
     * <code>
     *  {% set widget_service_params = {"template":"SfynxSmoothnessBundle:Login@@@@@@@@Resetting:reset_content.html.twig", "url_redirection": path_url('page_lamelee_menuwrapper_monespace', {'locale':locale})~'#profil'} %} 
     *  {{ getService('pi_app_admin.manager.authentication').renderSource('SfynxAuthBundle:User~_reset_default', 'fr_FR', widget_service_params)|raw }}
     * </code>
     *    
     * @param    $options    tableau d'options.
     * @access protected
     * @return void
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */
    public function renderConnexion($options = null)
    {
        $xmlConfig  = $this->getConfigXml();
        $lang       = $options['widget-lang'];
        $params     = array();        
        // if the configXml field of the widget isn't configured correctly.
        try {
            $xmlConfig    = new \Zend_Config_Xml($xmlConfig);
        } catch (\Exception $e) {
            return "  \n";
        }        
        // if the gedmo widget is defined correctly as a "lucene"
        if ( ($this->action == "connexion") && $xmlConfig->widgets->get('user') ) {
            $controller    = $xmlConfig->widgets->user->controller;        
            if ($this->isAvailableAction($controller)) {
                if ($xmlConfig->widgets->user->get('params')) {
                    $params = $xmlConfig->widgets->user->params->toArray();
                } else {
                    $params = array();
                }
                $params['widget-id']        = $options['widget-id'];
                $params['widget-lifetime']  = $options['widget-lifetime'];
                $params['widget-cacheable'] = ((int) $options['widget-cacheable']) ? true : false;
                $params['widget-update']    = $options['widget-update'];
                $params['widget-public']    = $options['widget-public'];
                $params['widget-ajax']      = ((int) $options['widget-ajax']) ? true : false;
                $params['widget-sluggify']  = ((int) $options['widget-sluggify']) ? true : false;
                
                return $this->renderService('pi_app_admin.manager.authentication', "$this->entity~$this->method", $lang, $params);
            } else {
                throw ExtensionException::optionValueNotSpecified("gedmo controller", __CLASS__);
            }
        } else {
            throw ExtensionException::optionValueNotSpecified("content", __CLASS__);
        }        
    }
    
    /**
     * Sets JS script.
     *
     * @param    array $options
     * @access public
     * @return void
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function scriptJs($options = null) {
        // We open the buffer.
        ob_start ();
        ?>
            
        <?php
        // We retrieve the contents of the buffer.
        $_content = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();
        
        return $_content;
    }
    
    /**
     * Sets Css script.
     *
     * @param array $options
     * @access public
     * @return void
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function scriptCss($options = null) {
        // We open the buffer.
        ob_start ();
        ?>
        
        <?php
        // We retrieve the contents of the buffer.
        $_content = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();
        
        return $_content;
    }
    
    /**
     * Sets Editor script.
     *
     * @param array $options
     * @access public
     * @return void
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function scriptEditor($options = null) {
        // We open the buffer.
        ob_start ();
        ?>
        
        <?php
        // We retrieve the contents of the buffer.
        $_content = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();
        
        return $_content;
    }    
}