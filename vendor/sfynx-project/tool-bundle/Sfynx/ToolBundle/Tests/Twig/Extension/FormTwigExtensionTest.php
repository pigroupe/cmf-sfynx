<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   Tool
 * @package    Test
 * @subpackage Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Tests\Twig\Extension;

use Sfynx\ToolBundle\Twig\Extension\PiFormExtension;
use Symfony\Component\Form\FormError;
use \Phake;

/**
 * @category   Tool
 * @package    Test
 * @subpackage Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class FormTwigExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
    }
    
    public function testGroupedFormErrors()
    {
        $extension = new PiFormExtension($this->container);

        $functions = $extension->getFunctions();
        $this->assertArrayHasKey('group_form_view_errors', $functions);

        $view = Phake::mock('Symfony\Component\Form\FormView');
        $view->vars['errors'] = array(new FormError('test'));

        $child = Phake::mock('Symfony\Component\Form\FormView');
        $child->vars['errors'] = array(new FormError('childError'));

        $child2 = Phake::mock('Symfony\Component\Form\FormView');
        $child2->vars['errors'] = array(new FormError('childError'));

        Phake::when($view)->count()->thenReturn(3);
        $view->children = array($child, $child2);

        $errors = $extension->getFormViewErrors($view);
        $this->assertInternalType('array', $errors);
        $this->assertCount(2, $errors);
        $this->assertEquals('test', $errors[0]->getMessage());
        $this->assertContains('childError', $errors[1]->getMessage());
    }
}
