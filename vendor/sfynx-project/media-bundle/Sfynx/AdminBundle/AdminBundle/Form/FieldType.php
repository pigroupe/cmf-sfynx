<?php

namespace App\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cle', 'text', array('label' => 'Clé'))
            ->add('fieldTranslation', 'collection', array(
               'type' => new FieldTranslationType(),
               'by_reference' => false,
               'allow_add' => true,
               'allow_delete' => true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\AdminBundle\Entity\Field'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_adminbundle_field';
    }
}
