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

use Sfynx\CmfBundle\Repository\TranslationPageRepository;
use Doctrine\ORM\EntityRepository;

/**
 * Description of the TranslationCssJsPageType form.
 *
 * @subpackage   Admin_Form
 * @package    CMS_Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class TranslationCssJsPageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled', 'checkbox', array(
                    'data'  => true,
                    'label'    => 'pi.form.label.field.enabled',
            ))
            ->add('langCode', 'entity', array(
                    'class' => 'SfynxAuthBundle:Langue',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('k')
                        ->select('k')
                        ->where('k.enabled = :enabled')
                        ->orderBy('k.label', 'ASC')
                        ->setParameter('enabled', 1);
                    },
                    "label"    => "pi.form.label.field.language",
                    "attr" => array(
                            "class"=>"pi_simpleselect",
                    ),            
            ))
            ->add('langStatus', 'choice', array(
                    'choices'   => TranslationPageRepository::getAvailableLangStatus(),
                    'required'  => true,
                    'multiple'    => false,
                    'expanded' => true,
                    "attr" => array(
                            "class"=>"greyarrow",
                    ),
            ))
            ->add('status', 'choice', array(
                    'choices'   => TranslationPageRepository::getAvailableStatus(),
                    'required'  => true,
                    'multiple'    => false,
                    'expanded' => true,
            ))
            
            ->add('texte', 'textarea', array(
                    "attr" => array(
                            "class"    =>"pi_editor",
                    ),
            ))
        ;
    }

    public function getName()
    {
        return 'piapp_adminbundle_translationpagetype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Sfynx\CmfBundle\Entity\TranslationPage',
        ));
    }    
}