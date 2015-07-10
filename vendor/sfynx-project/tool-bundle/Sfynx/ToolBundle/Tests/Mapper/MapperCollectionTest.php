<?php

namespace Sfynx\ToolBundle\Tests\Mapper;

use Sfynx\ToolBundle\Util\PiMapperManager;
use Phake;

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
