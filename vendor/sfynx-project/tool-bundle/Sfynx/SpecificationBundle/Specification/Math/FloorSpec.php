<?php

namespace Sfynx\SpecificationBundle\Specification\Math;

use Sfynx\SpecificationBundle\Specification\Builder\InterfaceSpecification;
use Sfynx\SpecificationBundle\Specification\Math\abstractSpecification;

/**
 * This file is part of the <Trigger> project.
 * true if floor(c)
 * 
 * @category   Trigger
 * @package    Specification
 * @subpackage Object
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class FloorSpec extends abstractSpecification implements InterfaceSpecification {

    private $specification1;

    function __construct($specification1) {
        $this->specification1 = $specification1;
    }

    public function isSatisfiedBy($object = null)
    {
        if ($specification1 instanceof  InterfaceSpecification)
        {
            $a = $specification1->isSatisfiedBy($object);
        } else {
            $a = $specification1;
        }   
        
        return (int) floor($a);
    } 
}
