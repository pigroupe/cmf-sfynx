<?php
/**
 * Dependency Injection Configuration for NosBelIdeesWebserviceBundle
 *
 * @package    NosBelIdeesWebserviceBundle
 * @subpackage DependencyInjection
 * @author     Alexis Janvier <alexis.janvier@rappfrance.com>
 */

namespace Sfynx\WsseBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * See {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sfynx_ws_wsse');

        return $treeBuilder;
    }
}
