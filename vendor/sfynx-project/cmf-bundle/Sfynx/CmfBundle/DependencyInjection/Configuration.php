<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @category   Cmf
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
namespace Sfynx\CmfBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * @category   Cmf
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
        $rootNode = $treeBuilder->root('sfynx_cmf');
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $this->addCacheConfig($rootNode);
        $this->addAdminConfig($rootNode);
        $this->addSeoConfig($rootNode);
        $this->addPageConfig($rootNode);

        return $treeBuilder;
    }
    
    /**
     * Admin config
     *
     * @param ArrayNodeDefinition $rootNode ArrayNodeDefinition Class
     *
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function addCacheConfig(ArrayNodeDefinition $rootNode)
    {
        $rootNode
        ->children()
            ->arrayNode('cache_dir')
                ->addDefaultsIfNotSet()
                ->children()                
                    ->scalarNode('etag')->isRequired()->defaultValue('%kernel.root_dir%/cachesfynx/Etag/')->cannotBeEmpty()->end()
                    ->scalarNode('indexation')->isRequired()->defaultValue('%kernel.root_dir%/cachesfynx/Indexation')->cannotBeEmpty()->end()
                    ->scalarNode('widget')->isRequired()->defaultValue("%kernel.root_dir%/cachesfynx/Widget")->cannotBeEmpty()->end()
                    ->scalarNode('seo')->isRequired()->defaultValue("%kernel.root_dir%/cachesfynx/Seo")->cannotBeEmpty()->end()
                ->end()        
            ->end()
        ->end();
    }       
    
    /**
     * Admin config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
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
                    ->scalarNode('context_menu_theme')->defaultValue('pi2')->cannotBeEmpty()->end()
                ->end()        
            ->end()
        ->end();
    }    
    
    /**
     * Page config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function addPageConfig(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('page')
                ->addDefaultsIfNotSet()
                ->children()

                    ->booleanNode('homepage_deletewidget')->isRequired()->defaultValue(true)->end()                        
                    ->booleanNode('page_management_by_user_only')->isRequired()->defaultValue(false)->end()    
                    ->booleanNode('indexation_authorized_automatically')->isRequired()->defaultValue(false)->end()                       
                    ->booleanNode('memcache_enable_all')->isRequired()->defaultValue(false)->end()
                        
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

                    ->arrayNode('esi')
                    ->isRequired()
                        ->children()
                            ->booleanNode('authorized')->isRequired()->defaultValue(false)->end()
                            ->scalarNode('encrypt_key')->isRequired()->defaultValue("9eu9ghv9")->end()
                            ->booleanNode('force_widget_tag_esi_for_varnish')->isRequired()->defaultValue(false)->end()
                            ->booleanNode('force_private_response_for_all')->isRequired()->defaultValue(false)->end()
                            ->booleanNode('force_private_response_only_with_authentication')->isRequired()->defaultValue(true)->end()
                            ->booleanNode('disable_after_post_request')->isRequired()->defaultValue(true)->end()
                        ->end()
                    ->end()         

                    ->arrayNode('widget')
                    ->isRequired()
                        ->children()
                            ->booleanNode('render_service_with_ttl')->isRequired()->defaultValue(false)->end()
                            ->booleanNode('render_service_with_ajax')->isRequired()->defaultValue(false)->end()
                            ->booleanNode('ajax_disable_after_post_request')->isRequired()->defaultValue(true)->end()
                        ->end()
                    ->end()    
                    
                    ->arrayNode('scop')
                    ->isRequired()
                        ->children()
                            ->booleanNode('authorized')->isRequired()->defaultValue(false)->end()

                            ->arrayNode('globals')
                            ->isRequired()
                                ->children()                                
                                    ->arrayNode('navigator')
                                        ->prototype('scalar')->end()
                                    ->end()       
                                                                 
                                    ->arrayNode('mobile')
                                        ->prototype('scalar')->end()
                                    ->end()     
                                                                 
                                    ->arrayNode('tablet')
                                        ->prototype('scalar')->end()
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
     * Layout config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    protected function addSeoConfig(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('seo')
                    ->addDefaultsIfNotSet()
                    ->children()
                    
                        ->arrayNode('meta_head')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('author')->defaultValue('Sfynx')->cannotBeEmpty()->end()                                
                                ->scalarNode('copyright')->defaultValue('Sfynx')->cannotBeEmpty()->end()                                    
                                ->scalarNode('title')->defaultValue('')->end()                                 
                                ->scalarNode('description')->defaultValue('')->end()                                    
                                ->scalarNode('keywords')->defaultValue('')->end()
                                ->scalarNode('og_title_add')->defaultValue('')->cannotBeEmpty()->end()                                    
                                ->scalarNode('og_type')->defaultValue('')->cannotBeEmpty()->end()
                                ->scalarNode('og_image')->defaultValue('')->cannotBeEmpty()->end()
                                ->scalarNode('og_site_name')->defaultValue('')->cannotBeEmpty()->end()
                                ->arrayNode('additions')->prototype('scalar')->end()->end()                                   
                            ->end()
                        ->end()          


                        ->arrayNode('redirection_oldurl_to_new_url')
                        ->isRequired()
                            ->children()
                                ->booleanNode('authorized')->isRequired()->defaultValue(false)->end()
                                ->scalarNode('file_name')->isRequired()->defaultValue("seo_links.yml")->cannotBeEmpty()->end()
                            ->end()
                        ->end()                        
                        
                    ->end()
                ->end()
            ->end();
    }  
}
