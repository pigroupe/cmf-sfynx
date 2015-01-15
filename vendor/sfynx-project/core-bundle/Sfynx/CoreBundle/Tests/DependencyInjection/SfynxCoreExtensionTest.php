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
namespace Sfynx\CoreBundle\Tests\DependencyInjection;

use Sfynx\CoreBundle\DependencyInjection\SfynxCoreExtension;
use Phake;

/**
 * @subpackage Core
 * @package    Tests
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxCoreExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadsSomeConfigurationFile()
    {
        $extension = new SfynxCoreExtension();
        $builder = Phake::mock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $extension->load(array(), $builder);

        Phake::verify($builder, Phake::atLeast(1))->addResource(Phake::anyParameters());
    }
}
