<?php
/**
 * This file is part of the <Translator> project.
 *
 * @category   Translator
 * @package    Test
 * @subpackage DependencyInjection
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\TranslatorBundle\Tests\DependencyInjection;

use Sfynx\TranslatorBundle\DependencyInjection\SfynxTranslatorExtension;
use Phake;

/**
 * @category   Translator
 * @package    Test
 * @subpackage DependencyInjection
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxTranslatorExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadsSomeConfigurationFile()
    {
        $extension = new SfynxTranslatorExtension();
        $builder = Phake::mock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $extension->load(array(), $builder);

        Phake::verify($builder, Phake::atLeast(1))->addResource(Phake::anyParameters());
    }
}
