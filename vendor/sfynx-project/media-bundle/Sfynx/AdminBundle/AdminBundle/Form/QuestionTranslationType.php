<?php

namespace App\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class QuestionTranslationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('langue','entity', array(
                'class' => 'AppAdminBundle:Langue',
                'property' => 'libelle',
                'required'  => true,
                'multiple' => false,
                'expanded'  => false,
            ))
            ->add('libelle','text', array('label' => 'Libellé'))
            ->add('reponse1','text', array('label' => 'Réponse 1', 'attr' => array('info' => 'reponse'),'required' => false))
            ->add('reponse2','text', array('label' => 'Réponse 2', 'attr' => array('info' => 'reponse'),'required' => false))
            ->add('reponse3','text', array('label' => 'Réponse 3', 'attr' => array('info' => 'reponse'),'required' => false))
            ->add('reponse4','text', array('label' => 'Réponse 4', 'attr' => array('info' => 'reponse'),'required' => false))
            ->add('reponse5','text', array('label' => 'Réponse 5', 'attr' => array('info' => 'reponse'),'required' => false))
            ->add('reponse6','text', array('label' => 'Réponse 6', 'attr' => array('info' => 'reponse'),'required' => false))




        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\AdminBundle\Entity\QuestionTranslation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_adminbundle_questiontranslation';
    }
}
