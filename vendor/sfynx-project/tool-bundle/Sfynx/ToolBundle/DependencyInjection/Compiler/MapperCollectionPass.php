<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage Tool
 * @package    Configuration
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * his CompilerPass registers all services with the right tag as mapper services
 *
 * @subpackage Tool
 * @package    Configuration
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class MapperCollectionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('sfynx.tool.mapper_collection')) {
            return;
        }

        $definition = $container->getDefinition('sfynx.tool.mapper_collection');

        $servicesMapper = $container->findTaggedServiceIds('mapper');
        if (is_array($servicesMapper)) {
            foreach (array_keys($servicesMapper) as $mapperId) {
                $definition->addMethodCall('add', array(new Reference($mapperId)));
            }
        }
    }
}
