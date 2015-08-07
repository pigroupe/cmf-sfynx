<?php

namespace Sfynx\SpecificationBundle\Specification\Math;

use Sfynx\SpecificationBundle\Specification\Builder\InterfaceSpecification;
use Sfynx\SpecificationBundle\Specification\Math\abstractSpecification;

/**
 * This file is part of the <Trigger> project.
 * true if $a - $b
 * 
 * @category   Trigger
 * @package    Specification
 * @subpackage Object
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SubtractSpec extends abstractSpecification implements InterfaceSpecification {

    private $specification1;
    private $specification2;

    function __construct($specification1, $specification2) {
        $this->specification1 = $specification1;
        $this->specification2 = $specification2;
    }

    public function isSatisfiedBy($object = null)
    {
        list($a, $b) = $this->setValues($this->specification1, $this->specification2, $object);
        
        return $a - $b;
    } 
}
