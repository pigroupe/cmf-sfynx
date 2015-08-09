<?php
/**
 * This file is part of the <Browser> project.
 *
 * @category   Browser
 * @package    Test
 * @subpackage DependencyInjection
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\BrowserBundle\Tests\DependencyInjection;

use Sfynx\BrowserBundle\DependencyInjection\SfynxBrowserExtension;
use Phake;

/**
 * @category   Browser
 * @package    Test
 * @subpackage DependencyInjection
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxBrowserExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadsSomeConfigurationFile()
    {
        $extension = new SfynxBrowserExtension();
        $builder = Phake::mock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $extension->load(array(), $builder);

        Phake::verify($builder, Phake::atLeast(1))->addResource(Phake::anyParameters());
    }
}
