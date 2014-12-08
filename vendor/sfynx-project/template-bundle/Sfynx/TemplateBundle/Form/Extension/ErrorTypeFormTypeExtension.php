<?php
/**
 * This file is part of the <Template> project.
 *
 * @subpackage   Extension
 * @package    Form
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-09
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\TemplateBundle\Form\Extension;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * ErrorTypeForm Extension
 *
 * @subpackage   Extension
 * @package    Form
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ErrorTypeFormTypeExtension extends AbstractTypeExtension
{
    protected $error_type;
    
    public function __construct(array $options)
    {
        $this->error_type = $options['error_type'];
    }
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['error_type'] = $options['error_type'];
        $view->vars['error_delay'] = $options['error_delay'];
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'error_type' => $this->error_type,
            'error_delay'=> false
       ));
    }
    public function getExtendedType()
    {
        return 'form';
    }
}
