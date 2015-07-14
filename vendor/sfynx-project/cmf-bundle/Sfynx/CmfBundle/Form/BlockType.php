<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Form
 * @package    CMS_Form
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-07
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sfynx\CmfBundle\Form\WidgetType;

/**
 * Description of the PageByBlockType form.
 *
 * @subpackage   Admin_Form
 * @package    CMS_Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class BlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled', 'checkbox', array(
                    'data'  => true,
                    'label'    => 'pi.form.label.field.enabled',
            ))
            ->add('name', 'text', array(
                 'label' => "pi.form.label.field.name"
             ))
            ->add('configCssClass')
            ->add('configXml')
            ->add('position')           
        ;
    }

    public function getName()
    {
        return 'piapp_adminbundle_blocktype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Sfynx\CmfBundle\Entity\Block',
        ));
    }    
}