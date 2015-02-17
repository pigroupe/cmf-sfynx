<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Auth
 * @package    DependencyInjection
 * @subpackage Configuration
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * 
 * @category   Auth
 * @package    DependencyInjection
 * @subpackage Configuration
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('symf_auth');
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $this->addLoginFailureConfig($rootNode);
        $this->addLocaleConfig($rootNode);
        $this->addBrowserConfig($rootNode);
        $this->addRedirectionLoginConfig($rootNode);
        $this->addLayoutConfig($rootNode);
        $this->addThemeConfig($rootNode);

        return $treeBuilder;
    }
    
    /**
     * Login failure config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addLoginFailureConfig(ArrayNodeDefinition $rootNode)
    {
    	$rootNode
    	->children()
        	->arrayNode('loginfailure')
        	    ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('authorized')->isRequired()->defaultValue(true)->end()
                    ->scalarNode('time_expire')->defaultValue(3600)->end()
                    ->scalarNode('connection_attempts')->defaultValue(3)->end()
                    ->scalarNode('cache_dir')->defaultValue('%kernel.root_dir%/cachesfynx/loginfailure/')->cannotBeEmpty()->end()
                ->end()
            ->end()
    	->end();
    }      
    
    /**
     * Locale config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addLocaleConfig(ArrayNodeDefinition $rootNode)
    {
    	$rootNode
    	->children()
        	->arrayNode('locale')
        	    ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('authorized')->prototype('scalar')->end()->defaultValue(array('fr_FR', 'en_GB', 'ar_SA'))->end()
                    ->scalarNode('cache_file')->defaultValue('%kernel.root_dir%/cachesfynx/languages.json')->cannotBeEmpty()->end()
                ->end()
            ->end()
    	->end();
    }  
    
    /**
     * Browser config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addBrowserConfig(ArrayNodeDefinition $rootNode)
    {
    	$rootNode
    	->children()
        	->arrayNode('browser')
        	    ->addDefaultsIfNotSet()
        	    ->children()
        	        ->booleanNode('switch_language_authorized')->isRequired()->defaultValue(false)->end()
        	        ->booleanNode('switch_layout_mobile_authorized')->isRequired()->defaultValue(false)->end()
        	    ->end()
        	->end()        	
    	->end();
    }    
    
    /**
     * Redirection login config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addRedirectionLoginConfig(ArrayNodeDefinition $rootNode)
    {
    	$rootNode
    	->children()
            ->arrayNode('default_login_redirection')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('redirection')->defaultValue('admin_homepage')->cannotBeEmpty()->end()
                    ->scalarNode('template')->defaultValue('layout-pi-admin.html.twig')->cannotBeEmpty()->end()
                ->end()
            ->end()
    	->end();
    } 

    /**
     * Layout default config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addLayoutConfig(ArrayNodeDefinition $rootNode)
    {
    	$rootNode
    	->children()
        	->arrayNode('default_layout')
        	    ->addDefaultsIfNotSet()
        	    ->children()
                	    ->arrayNode('init_pc')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('template')->defaultValue('layout-pi-page1.html.twig')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                        
                        ->arrayNode('init_mobile')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('template')->defaultValue('Default')->cannotBeEmpty()->end()                                    
                            ->end()
                        ->end()
            	->end()
        	->end()
    	->end();
    }  

    /**
     * Browser config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addThemeConfig(ArrayNodeDefinition $rootNode)
    {
    	$rootNode
    	->children()
        	->arrayNode('theme')
            	->addDefaultsIfNotSet()
            	->children()
            	    ->scalarNode('name')->isRequired()->defaultValue("smoothness")->cannotBeEmpty()->end()
            	    ->scalarNode('login')->isRequired()->defaultValue('SfynxSmoothnessBundle::Login\\')->cannotBeEmpty()->end()
            	    ->scalarNode('layout')->isRequired()->defaultValue('SfynxSmoothnessBundle::Layout\\')->cannotBeEmpty()->end()
            	    
            	    ->arrayNode('global')
                	    ->addDefaultsIfNotSet()
                	    ->children()
                	        ->scalarNode('layout')->isRequired()->defaultValue('SfynxSmoothnessBundle::Layout\\layout-global-cmf.html.twig')->cannotBeEmpty()->end()
                	        ->scalarNode('css')->isRequired()->defaultValue('SfynxSmoothnessBundle::Layout\\layout-global-cmf.html.twig')->cannotBeEmpty()->end()
                	    ->end()
            	    ->end()
            	    
            	    ->arrayNode('ajax')
                	    ->addDefaultsIfNotSet()
                	    ->children()
                	        ->scalarNode('layout')->isRequired()->defaultValue('SfynxSmoothnessBundle::Layout\\layout-ajax.html.twig')->cannotBeEmpty()->end()
                	        ->scalarNode('css')->isRequired()->defaultValue('SfynxSmoothnessBundle::Layout\\layout-ajax.html.twig')->cannotBeEmpty()->end()
                	    ->end()
            	    ->end()           	    
            	    
            	    ->arrayNode('error')
            	        ->addDefaultsIfNotSet()
                	    ->children()
                	        ->scalarNode('route_name')->defaultValue('')->end()
            	            ->scalarNode('html')->defaultValue('@SfynxSmoothnessBundle/Resources/views/Error/error.html.twig')->end()
                	    ->end()
            	    ->end()
            	    
            	    ->arrayNode('admin')
                	    ->addDefaultsIfNotSet()
                	    ->children()
                	        ->scalarNode('pc')->isRequired()->defaultValue('SfynxSmoothnessBundle::Layout\\Pc\\')->cannotBeEmpty()->end()
                    	    ->scalarNode('mobile')->isRequired()->defaultValue('SfynxSmoothnessBundle::Layout\\Mobile\\Admin\\')->cannotBeEmpty()->end()
                    	    ->scalarNode('css')->defaultValue('bundles/sfynxsmoothness/admin/screen.css')->end()
                    	    ->scalarNode('home')->isRequired()->defaultValue('SfynxSmoothnessBundle:Home:admin.html.twig')->cannotBeEmpty()->end()
                    	    ->scalarNode('dashboard')->isRequired()->defaultValue('dashboard.default.html.twig')->cannotBeEmpty()->end()

                    	    ->arrayNode('grid')
                        	    ->addDefaultsIfNotSet()
                        	    ->children()
                        	        ->scalarNode('img')->isRequired()->defaultValue('/bundles/sfynxsmoothness/admin/grid/')->cannotBeEmpty()->end()
                        	        ->scalarNode('css')->defaultValue('')->end()                        	        
                        	        ->scalarNode('type')->isRequired()->defaultValue('simple')->end()
                        	        ->booleanNode('state_save')->isRequired()->defaultValue(false)->end()
                        	        ->scalarNode('row_select')->isRequired()->defaultValue('multi')->end()
                        	        ->booleanNode('pagination')->isRequired()->defaultValue(true)->end()
                        	        ->scalarNode('pagination_type')->isRequired()->defaultValue("full_numbers")->end()
                        	        ->booleanNode('pagination_top')->isRequired()->defaultValue(false)->end()
                        	        ->scalarNode('lengthmenu')->isRequired()->defaultValue(20)->end()
                        	        ->booleanNode('filters_tfoot_up')->isRequired()->defaultValue(true)->end()
                        	        ->booleanNode('filters_active')->isRequired()->defaultValue(false)->end()
                        	    ->end()
                    	    ->end()
                    	    
                    	    ->arrayNode('form')
                        	    ->addDefaultsIfNotSet()
                        	    ->children()
                        	        ->scalarNode('builder')->isRequired()->defaultValue('SfynxSmoothnessBundle:Form')->cannotBeEmpty()->end()
                        	        ->scalarNode('template')->isRequired()->defaultValue('SfynxSmoothnessBundle:Form:fields.html.twig')->cannotBeEmpty()->end()
                            	    ->scalarNode('css')->defaultValue('')->end()
                        	    ->end()
                    	    ->end()                    	    
                    	    
                    	    ->scalarNode('flash')->isRequired()->defaultValue("SfynxFlatlabBundle:Flash:flash.html.twig")->cannotBeEmpty()->end()
                	    ->end()
            	    ->end()            	    
            	    
            	    ->arrayNode('front')
                	    ->addDefaultsIfNotSet()
                	    ->children()
                    	    ->scalarNode('pc')->isRequired()->defaultValue('SfynxSmoothnessBundle::Layout\\Pc\\')->cannotBeEmpty()->end()
                    	    ->scalarNode('pc_path')->isRequired()->defaultValue('@SfynxSmoothnessBundle/Resources/views/Layout/Pc/')->cannotBeEmpty()->end()
                    	    ->scalarNode('mobile')->isRequired()->defaultValue('SfynxSmoothnessBundle::Layout\\Mobile\\')->cannotBeEmpty()->end()
                    	    ->scalarNode('mobile_path')->isRequired()->defaultValue('@SfynxSmoothnessBundle/Resources/views/Layout/Mobile/')->cannotBeEmpty()->end()
                    	    ->scalarNode('css')->defaultValue('bundles/sfynxsmoothness/front/screen.css')->end()
                	    ->end()
            	    ->end()            	    
            	    
            	    ->arrayNode('connexion')
                	    ->addDefaultsIfNotSet()
                	    ->children()
                	        ->scalarNode('login')->isRequired()->defaultValue('SfynxSmoothnessBundle::Login\\Security\\login-layout.html.twig')->cannotBeEmpty()->end()
                	        ->scalarNode('widget')->isRequired()->defaultValue('SfynxSmoothnessBundle::Login\\Security\\connexion-widget.html.twig')->cannotBeEmpty()->end()
                	    ->end()
            	    ->end()
            	                	    
            	->end()
        	->end()
    	->end();
    }    
       
}
