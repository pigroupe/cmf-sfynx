<?php
/**
 * This file is part of the <Template> project.
 *
 * @subpackage   Template
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-03-10
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\TemplateBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * 
 * @subpackage   Template
 * @package    Configuration
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
        $rootNode = $treeBuilder->root('sfynx_template');
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $this->addFormConfig($rootNode);

        return $treeBuilder;
    }

    /**
     * Form config
     *
     * @param $rootNode \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
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
                    ->booleanNode('show_legend')->defaultValue(true)->end()
                    ->booleanNode('show_child_legend')->defaultValue(false)->end()
                    ->scalarNode('error_type')->defaultValue('inline')->cannotBeEmpty()->end()
                ->end()
            ->end()
    	->end();
    } 

}