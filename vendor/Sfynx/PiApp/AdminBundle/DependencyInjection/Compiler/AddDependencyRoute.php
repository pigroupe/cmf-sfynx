<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   CMF
 * @package    Route
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * route cache management.
 *
 * @category   CMF
 * @package    Route
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class AddDependencyRoute implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $routeLoader = $container->getDefinition('pi.route.route_loader');
    }
}