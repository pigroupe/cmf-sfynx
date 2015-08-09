<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   Tool
 * @package    Tests
 * @subpackage Service
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Tests\Mapper;

use Sfynx\ToolBundle\Util\PiMapperManager;
use Phake;

/**
 * @category   Tool
 * @package    Test
 * @subpackage Service
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class MapperCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testUsesAddedMappers()
    {
        $collection = new PiMapperManager();

        $mapper = Phake::mock('Sfynx\ToolBundle\Builder\PiMapperManagerBuilderInterface');
        Phake::when($mapper)->supports('test')
            ->thenReturn(true);

        $collection->add($mapper);
        $collection->map('<test></test>');

        Phake::verify($mapper)->supports('test');
        Phake::verify($mapper)->map('<test></test>', $collection);
    }
}
