<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   Bundle
 * @package    DependencyInjection
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * @category   Bundle
 * @package    DependencyInjection
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pi_app_admin');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $this->addAdminConfig($rootNode);
        $this->addLayoutConfig($rootNode);
        $this->addPageConfig($rootNode);
        $this->addTranslationConfig($rootNode);
        $this->addFormConfig($rootNode);
        $this->addMailConfig($rootNode);
        $this->addCookiesConfig($rootNode);
        $this->addPermissionConfig($rootNode);

        return $treeBuilder;
    }
    
    /**
     * Admin config
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function addAdminConfig(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('admin')
                ->addDefaultsIfNotSet()
                ->children()
                
                    ->scalarNode('context_menu_theme')
                        ->defaultValue('pi2')
                        ->cannotBeEmpty()
                        ->end()

                    ->scalarNode('grid_index_css')
                        ->defaultValue('style-grid-7.css')
                        ->cannotBeEmpty()
                        ->end()
                        
                    ->scalarNode('grid_show_css')
                        ->defaultValue('style-grid-5.css')
                        ->cannotBeEmpty()
                        ->end()
                        
                    ->scalarNode('theme_css')
                        ->defaultValue('rocket')
                        ->cannotBeEmpty()
                        ->end()
                        
                ->end()
        
            ->end()
        ->end();
    }    
    
    /**
     * Page config
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function addPageConfig(ArrayNodeDefinition $rootNode)
    {
        $supportedMethods = array(
        		'URL-wrapper',
        		'socket',
        		'cURL',
        		'local',
        );
        
        $rootNode
            ->children()
                ->arrayNode('page')
                ->addDefaultsIfNotSet()
                ->children()

                    ->booleanNode('homepage_deletewidget')->isRequired()
                        ->defaultValue(true)
                        ->end()
                        
                    ->booleanNode('page_management_by_user_only')->isRequired()
                        ->defaultValue(false)
                        ->end()     

                    ->booleanNode('indexation_authorized_automatically')->isRequired()
                        ->defaultValue(false)
                        ->end()                        

                    ->booleanNode('switch_layout_mobile_authorized')->isRequired()
                        ->defaultValue(false)
                        ->end()
                    
                    ->booleanNode('switch_language_browser_authorized')->isRequired()
                        ->defaultValue(false)
                        ->end()
                         
                    ->booleanNode('memcache_enable_all')->isRequired()
                        ->defaultValue(false)
                        ->end()
                        
                    ->arrayNode('refresh')->isRequired()
                    ->isRequired()
                        ->children()
                            ->booleanNode('allpage')->isRequired()->defaultValue(true)->end()
                            ->booleanNode('allpage_containing_snippet')->isRequired()->defaultValue(true)->end()
                            ->booleanNode('css_js_cache_file')->isRequired()->defaultValue(true)->end()
                        ->end()
                    ->end()                            
                        
                    ->arrayNode('route')->isRequired()
                    ->isRequired()
                        ->children()
                    	    ->booleanNode('with_prefix_locale')->isRequired()->defaultValue(false)->end()
                    	    ->booleanNode('single_slug')->isRequired()->defaultValue(false)->end()
                        ->end()
                    ->end()                       
                        
                    ->arrayNode('seo_redirection')
                    ->isRequired()
                        ->children()
                    	    ->booleanNode('seo_authorized')->isRequired()->defaultValue(false)->end()
	                        ->scalarNode('seo_repository')->isRequired()->defaultValue("")->end()
    	                    ->scalarNode('seo_file_name')->isRequired()->defaultValue("")->end()
                        ->end()
                    ->end()  

                    ->arrayNode('esi')
                    ->isRequired()
                        ->children()
                            ->booleanNode('force_private_response_for_all')->isRequired()->defaultValue(false)->end()
                            ->booleanNode('force_private_response_only_with_authentication')->isRequired()->defaultValue(true)->end()
                            ->booleanNode('disable_after_post_request')->isRequired()->defaultValue(true)->end()
                        ->end()
                    ->end()         

                    ->arrayNode('widget')
                    ->isRequired()
                        ->children()
                            ->booleanNode('render_service_with_ajax')->isRequired()->defaultValue(false)->end()
                            ->booleanNode('ajax_disable_after_post_request')->isRequired()->defaultValue(true)->end()
                        ->end()
                    ->end()    

                    
                    ->arrayNode('scop')
                    ->isRequired()
                        ->children()
                            ->booleanNode('authorized')->isRequired()->defaultValue(false)->end()
                            
                            ->arrayNode('browscap')
                            ->isRequired()
                                ->children()
                                    ->scalarNode('cache_dir')->defaultValue(null)->end()
                                    ->scalarNode('local_file')->defaultValue(null)->end()
                                    ->scalarNode('cache_filename')->defaultValue('cache.php')->end()
                                    ->scalarNode('ini_filename')->defaultValue('browscap.ini')->end()
                                    ->scalarNode('remote_ini_url')->defaultValue('http://browscap.org/stream?q=Full_PHP_BrowsCapINI')->end()
                                    ->scalarNode('remote_ver_url')->defaultValue('http://browscap.org/version')->end()
                                    ->booleanNode('lowercase')->defaultValue(false)->end()
                                    ->booleanNode('silent')->defaultValue(false)->end()
                                    ->scalarNode('timeout')->defaultValue(5)->end()
                                    ->scalarNode('update_interval')->defaultValue(432000)->end()
                                    ->scalarNode('error_interval')->defaultValue(7200)->end()
                                    ->booleanNode('do_auto_update')->defaultValue(true)->end()
                                    ->scalarNode('update_method')
                                        ->validate()
                                            ->ifNotInArray($supportedMethods)
                                            ->thenInvalid('The method "%s" is not supported. Please choose one of ' . json_encode($supportedMethods))
                                        ->end()
                                        ->cannotBeOverwritten()
                                        ->defaultValue('cURL')
                                    ->end()
                                ->end()
                            ->end()
                            

                            ->arrayNode('globals')
                            ->isRequired()
                                ->children()
                                
                                    ->arrayNode('navigator')
                                        ->prototype('scalar')
                                        ->end()
                                    ->end()
                                    
                                    ->arrayNode('mobile')
                                        ->prototype('scalar')
                                        ->end()
                                    ->end()                                    
                                    
                                    ->arrayNode('tablet')
                                        ->prototype('scalar')
                                        ->end()
                                    ->end()
                                    
                                ->end()
                            ->end()                            
                            
                        ->end()
                    ->end()                    
                        
                ->end()
        
            ->end()
        ->end();
    }
        
    /**
     * Form config
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function addFormConfig(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('form')
                    ->addDefaultsIfNotSet()                    
                    ->children()
                    
                        ->booleanNode('show_legend')
                            ->defaultValue(true)
                            ->end()
                            
                        ->booleanNode('show_child_legend')
                            ->defaultValue(false)
                            ->end()
                            
                        ->scalarNode('error_type')
                            ->defaultValue('inline')
                            ->cannotBeEmpty()
                            ->end()
                        
                    ->end()
                    
                ->end()
            ->end();
    }
    
    /**
     * Translation config
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addTranslationConfig(ArrayNodeDefinition $rootNode)
    {
    	$rootNode
        	->children()
            	->arrayNode('translation')
            	    ->addDefaultsIfNotSet()
            	    ->children()
            
                    	->scalarNode('defaultlocale_setting')
                    	    ->defaultValue(true)
                    	    ->end()
            
            	->end()
        
        	->end()
    	->end();
    }    
    
    /**
     * Mail config
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addMailConfig(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('mail')
                    ->addDefaultsIfNotSet()
                    ->children()
                
                        ->scalarNode('overloading_mail')
                            ->defaultValue('')
                            ->end()
                    
                ->end()
        
            ->end()
        ->end();
    }   
    
    /**
     * Cookies config
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addCookiesConfig(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('cookies')
                    ->addDefaultsIfNotSet()
                    ->children()
                    
                        ->scalarNode('application_id')
                            ->cannotBeEmpty()
                            ->end()                  
                
                        ->booleanNode('date_expire')
                            ->defaultValue(true)
                            ->end()
                    
                        ->scalarNode('date_interval')
                            ->defaultValue("PT4H")
                            ->end()

                ->end()
        
            ->end()
        ->end();
    }    
    
    /**
     * Permission config
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addPermissionConfig(ArrayNodeDefinition $rootNode)
    {
    	$rootNode
	    	->children()
		    	->arrayNode('permission')
			    	->children()
			    	
			    		->booleanNode('restriction_by_roles')->isRequired()->defaultValue(false)->end()
			    				    	
				    	->arrayNode('authorization')
					    ->isRequired()
						   	->children()
						    	->booleanNode('prepersist')->defaultValue(false)->end()
						    	->booleanNode('preupdate')->defaultValue(false)->end()
						    	->booleanNode('preremove')->defaultValue(false)->end()
					    	->end()
				    	->end()
				    	
				    	->arrayNode('prohibition')
				    	->isRequired()
					    	->children()
						    	->booleanNode('preupdate')->defaultValue(false)->end()
						    	->booleanNode('preremove')->defaultValue(false)->end()
					    	->end()
				    	->end()				    	
				    	
			    	->end()
		    	->end()
	    	->end();
    }    
    
    /**
     * Layout config
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
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
                ->arrayNode('layout')
                    ->addDefaultsIfNotSet()
                    ->children()
                    
                        ->arrayNode('init_pc')
                            ->addDefaultsIfNotSet()
                            ->children()
                            
                                ->scalarNode('template_name')
                                    ->defaultValue('layout-pi-page1.html.twig')
                                    ->cannotBeEmpty()
                                    ->end()
                                    
                                ->scalarNode('route_redirection_name')
                                    ->defaultValue('home_page')
                                    ->cannotBeEmpty()
                                    ->end()
                                    
                            ->end()
                        ->end()
                        
                        ->arrayNode('init_mobile')
                            ->addDefaultsIfNotSet()
                            ->children()
                            
                                ->scalarNode('template_name')
                                    ->defaultValue('Default')
                                    ->cannotBeEmpty()
                                    ->end()
                                    
                                ->scalarNode('route_redirection_name')
                                    ->defaultValue('public_page_mobile')
                                    ->cannotBeEmpty()
                                    ->end()
                                    
                            ->end()
                        ->end()

                        
                        ->arrayNode('login_role')
                            ->addDefaultsIfNotSet()
                            ->children()
                            
                                ->scalarNode('redirection_admin')
                                    ->defaultValue('admin_homepage')
                                    ->cannotBeEmpty()
                                    ->end()
                                
                                ->scalarNode('redirection_user')
                                    ->defaultValue('home_page')
                                    ->cannotBeEmpty()
                                    ->end()
                                    
                                ->scalarNode('redirection_subscriber')
                                    ->defaultValue('home_page')
                                    ->cannotBeEmpty()
                                    ->end()
                                    
                                ->scalarNode('template_admin')
                                    ->defaultValue('layout-pi-admin.html.twig')
                                    ->cannotBeEmpty()
                                    ->end()

                                ->scalarNode('template_user')
                                    ->defaultValue('layout-pi-page2.html.twig')
                                    ->cannotBeEmpty()
                                    ->end()

                                ->scalarNode('template_subscriber')
                                    ->defaultValue('layout-pi-page2.html.twig')
                                    ->cannotBeEmpty()
                                    ->end()
                                    
                                    
                            ->end()
                        ->end()
                        
                        ->arrayNode('template')
                            ->addDefaultsIfNotSet()
                            ->children()
                            
                                ->scalarNode('template_connection')
                                    ->defaultValue('layout-security.html.twig')
                                    ->cannotBeEmpty()
                                    ->end()
                                
                                ->scalarNode('template_form')
                                    ->defaultValue('fields.html.twig')
                                    ->cannotBeEmpty()
                                    ->end()
                                
                                ->scalarNode('template_grid')
                                    ->defaultValue('grid.theme.html.twig')
                                    ->cannotBeEmpty()
                                    ->end()

                                ->scalarNode('template_flash')
                                    ->defaultValue('flash.html.twig')
                                    ->cannotBeEmpty()
                                    ->end()
                                    
                            ->end()
                        ->end()
                        

                        ->arrayNode('meta_head')
                            ->addDefaultsIfNotSet()
                            ->children()
                            
                                ->scalarNode('author')
                                    ->defaultValue('Orchestra')
                                    ->cannotBeEmpty()
                                    ->end()
                                
                                ->scalarNode('copyright')
                                    ->defaultValue('Orchestra')
                                    ->cannotBeEmpty()
                                    ->end()
                                    
                                ->scalarNode('title')
                                    ->defaultValue('')
                                    ->end()                                    
                                    
                                ->scalarNode('description')
                                    ->defaultValue('')
                                    ->end()
                                    
                                ->scalarNode('keywords')
                                    ->defaultValue('')
                                    ->end()

                                ->scalarNode('og_title_add')
                                    ->defaultValue('')
                                    ->cannotBeEmpty()
                                    ->end()
                                    
                                ->scalarNode('og_type')
                                    ->defaultValue('')
                                    ->cannotBeEmpty()
                                    ->end()

                                ->scalarNode('og_image')
                                    ->defaultValue('')
                                    ->cannotBeEmpty()
                                    ->end()

                                ->scalarNode('og_site_name')
                                    ->defaultValue('')
                                    ->cannotBeEmpty()
                                    ->end()                                    
                            
                            ->end()
                        ->end()                        
                        
                        
                        
                    ->end()
                ->end()
            ->end();
    }    
        
}
