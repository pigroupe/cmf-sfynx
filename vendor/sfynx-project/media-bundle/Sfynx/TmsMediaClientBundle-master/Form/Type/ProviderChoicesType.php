<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace Tms\Bundle\MediaClientBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProviderChoicesType extends AbstractType
{
    /**
     * @var array
     */
    private $providerChoices = array();

    /**
     * Constructor
     *
     * @param array $providerChoices
     */
    public function __construct($providerChoices)
    {
        foreach($providerChoices as $key => $choice) {
            $this->providerChoices[$key] = isset($choice[0]['alias']) ?
                $choice[0]['alias'] :
                $key
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('choices' => $this->providerChoices));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'provider_choices';
    }
}
