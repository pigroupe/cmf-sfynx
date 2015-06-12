<?php

namespace MyApp\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ActeurForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('nom', 'text', array('label' => 'acteur.nom'))
		->add('prenom', 'text', array('label' => 'acteur.prenom'))
		->add('dateNaissance', 'birthday', array('label' => 'acteur.dateNaissance'))
		->add('sexe', 'choice', array(
                    'choices' => array('F'=>'Féminin','M'=>'Masculin'),
                    'label' => 'acteur.sexe'
                    ));

    }

    public function getName()
    {
        return 'acteur';
    }
}