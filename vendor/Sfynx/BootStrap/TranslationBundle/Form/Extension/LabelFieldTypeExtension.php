<?php
/**
 * This file is part of the <Translation> project.
 *
 * @category   Admin_Form
 * @package    Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-09
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BootStrap\TranslationBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

 
class LabelFieldTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('label_attr', $options['label_attr']);
        $builder->setAttribute('label_render', $options['label_render']);
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['label_attr'] = $form->getConfig()->getAttribute('label_attr');
        $view->vars['label_render'] = $form->getConfig()->getAttribute('label_render');
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label_attr' => array(),
            'label_render' => true,
        ));
    }
    
    public function getExtendedType()
    {
        return 'field';
    }
}