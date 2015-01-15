<?php
/**
 * This file is part of the <Core> project.
 *
 * @subpackage Core
 * @package    Tests
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle;

use Sfynx\ToolBundle\DependencyInjection\Compiler\MapperCollectionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @subpackage Core
 * @package    Tests
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxToolBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MapperCollectionPass());
    }
}
