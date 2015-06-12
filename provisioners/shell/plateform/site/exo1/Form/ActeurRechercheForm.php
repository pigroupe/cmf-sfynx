<?php

namespace MyApp\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ActeurRechercheForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('motcle', 'text', array('label' => 'motcle'));
    }
	
    public function getName()
    {
        return 'acteurrecherche';
    }	
}