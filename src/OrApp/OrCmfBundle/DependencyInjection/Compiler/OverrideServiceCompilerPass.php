<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   BootStrap
 * @package    OverrideService
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace OrApp\OrCmfBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Adds tagged twig.extension services to the pi_app_admin twig service
 *
 * @category   BootStrap
 * @package    OverrideService
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sfynx.auth.login_handler');
        $definition->setClass('OrApp\OrCmfBundle\EventListener\HandlerLogin');    
    }
}