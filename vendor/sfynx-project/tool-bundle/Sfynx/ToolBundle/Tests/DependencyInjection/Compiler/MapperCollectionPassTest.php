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
namespace Sfynx\ToolBundle\Tests\DependencyInjection\Compiler;

use Sfynx\ToolBundle\DependencyInjection\Compiler\MapperCollectionPass;
use Phake;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @subpackage Core
 * @package    Tests
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class MapperCollectionPassTest extends \PHPUnit_Framework_TestCase
{
    public function testRegsitersServicesTaggedAsMappers()
    {
        $pass = new MapperCollectionPass();

        $mapper = Phake::mock('Sfynx\ToolBundle\Builder\PiMapperManagerBuilderInterface');
        $mapperCollectionDefinition = Phake::mock('Symfony\Component\DependencyInjection\Definition');

        $containerBuilder = Phake::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        Phake::when($containerBuilder)->hasDefinition('sfynx.tool.mapper_collection')
            ->thenReturn(true);
        Phake::when($containerBuilder)->getDefinition('sfynx.tool.mapper_collection')
            ->thenReturn($mapperCollectionDefinition);

        Phake::when($containerBuilder)
            ->findTaggedServiceIds('mapper');

        $pass->process($containerBuilder);

        Phake::verify($containerBuilder)
            ->findTaggedServiceIds('mapper');
    }
}
