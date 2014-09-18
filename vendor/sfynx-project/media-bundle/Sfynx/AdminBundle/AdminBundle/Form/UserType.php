<?php

namespace App\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints;

class UserType extends AbstractType
{
    
   
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled', 'checkbox', array('required' => false))
            ->add('username')
            ->add('email')
            ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'first_options' => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Confirmer le mot de passe'),
                    'invalid_message' => 'Les mots de passe ne correspondent pas.',
            		'constraints' => array(
            				new Constraints\NotBlank(),
            		),
            ))
            ->add('groups','entity', array(
                'class' => 'AppAdminBundle:Group',
                'property' => 'name',
                'required'  => true,
                'multiple' => true,
                'expanded'  => false,
            ))
        ;
    }
    
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\AdminBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_adminbundle_user';
    }
}
