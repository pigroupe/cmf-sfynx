<?php

namespace App\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldTranslationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('langue')
            ->add('value', 'textarea', array('label' => 'Valeur', 'attr' => array('class' => 'wysihtml5 form-control', 'rows' => '10')))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\AdminBundle\Entity\FieldTranslation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_adminbundle_fieldtranslation';
    }
}
