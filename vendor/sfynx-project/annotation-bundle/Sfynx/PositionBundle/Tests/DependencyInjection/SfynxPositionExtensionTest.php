<?php
/**
 * This file is part of the <Position> project.
 *
 * @category   Position
 * @package    Test
 * @subpackage DependencyInjection
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\PositionBundle\Tests\DependencyInjection;

use Sfynx\PositionBundle\DependencyInjection\SfynxPositionExtension;
use Phake;

/**
 * @category   Position
 * @package    Test
 * @subpackage DependencyInjection
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxPositionExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadsSomeConfigurationFile()
    {
        $extension = new SfynxPositionExtension();
        $builder = Phake::mock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $extension->load(array(), $builder);

        Phake::verify($builder, Phake::atLeast(1))->addResource(Phake::anyParameters());
    }
}
