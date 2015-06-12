<?php

namespace MyApp\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use MyApp\SiteBundle\Form\ActeurForm;

class FilmForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('titre', 'text', array('label' => 'film.titre'))
                ->add('description', 'textarea', array('label' => 'film.description'));
        
        $builder->add('categorie','entity', array(
            'class' => 'MyApp\SiteBundle\Entity\Categorie',
            'property' => 'nom',
            'multiple' => false,
            'required' => false,
            'label' => 'film.categorie'
            ));        

        $builder->add('acteurs', 'entity', array(
            'class' => 'MyApp\SiteBundle\Entity\Acteur',
            'property' => 'PrenomNom',
            'expanded' => true,
            'multiple' => true,
            'required' => false,
            'label' => 'film.acteurs'
            ));
    }
    public function getName()
    {
        return 'film';
    }
}