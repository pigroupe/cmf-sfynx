<?php
/**
 * This file is part of the <Ws-se> project.
 *
 * @category   Ws-se
 * @package    DependencyInjection
 * @subpackage Configuration
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
namespace Sfynx\WsseBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * See {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * 
 * @category   Ws-se
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
        $rootNode = $treeBuilder->root('sfynx_wsse');
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $this->addConfig($rootNode);

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
    protected function addConfig(ArrayNodeDefinition $rootNode)
    {
        $rootNode
        ->children()
            ->arrayNode('security')
                ->addDefaultsIfNotSet()
                ->children()                
                    ->scalarNode('cache_dir')->isRequired()->defaultValue('%kernel.cache_dir%/security/nonces')->cannotBeEmpty()->end()
                    ->scalarNode('nonce_lifetime')->isRequired()->defaultValue('300')->cannotBeEmpty()->end()
                ->end()        
            ->end()
        ->end();
    }      
}
