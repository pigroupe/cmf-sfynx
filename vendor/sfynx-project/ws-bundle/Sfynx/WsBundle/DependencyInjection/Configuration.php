<?php
/**
 * This file is part of the <web service> project.
 *
 * @category   Tool
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2013-03-26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\WsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * @category   Tool
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Configuration implements ConfigurationInterface {

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sfynx_ws');
        $this->addAuthConfig($rootNode);
        $this->addSSOConfig($rootNode);

        return $treeBuilder;
    }

    /**
     * Auth config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     * @return void
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addAuthConfig(ArrayNodeDefinition $rootNode) {
        $rootNode
                ->children()
                    ->arrayNode('auth')
                        ->children()

                            ->arrayNode('log')
                            ->isRequired()
                                ->children()
                                        ->scalarNode('dev')->isRequired()->end()
                                        ->scalarNode('test')->isRequired()->end()
                                        ->scalarNode('prod')->isRequired()->end()
                                ->end()
                            ->end()
                            
                            ->arrayNode('domains')
                            ->prototype('array')
	                            ->children()
	                            	->scalarNode('url')->isRequired()->end()
	                            	->scalarNode('key')->isRequired()->end()
	                            ->end()
                            ->end()
                            ->end()
                                                
                            ->arrayNode('handlers')
                            ->isRequired()
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('key')->isRequired()->end()
                                    ->scalarNode('method')->isRequired()->end()
                                    ->scalarNode('api')->isRequired()->end()
                                    ->scalarNode('format')->isRequired()->end()
                                ->end()
                            ->end()
                            ->end()
                            
                        ->end()
                    ->end()
                ->end();
    }
    
    /**
     * SSO config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *
     * @return void
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function addSSOConfig(ArrayNodeDefinition $rootNode)
    {
    	$rootNode
    	->children()
        	->arrayNode('sso')
        	->isRequired()
        	    ->children()
                	->scalarNode('prefix_host')->isRequired()->defaultValue('http://')->end()
                	->scalarNode('uri_proxy')->isRequired()->end()
                	->scalarNode('uri_proxy_login')->isRequired()->end()
                	->scalarNode('application_name')->isRequired()->defaultValue('sso-sfynx')->end()
            	->end()
        	->end()
    	->end();
    }    
    
}