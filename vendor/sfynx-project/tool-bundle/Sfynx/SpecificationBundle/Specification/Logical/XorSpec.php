<?php

namespace Sfynx\SpecificationBundle\Specification\Logical;

use Sfynx\SpecificationBundle\Specification\Builder\InterfaceSpecification;
use Sfynx\SpecificationBundle\Specification\Logical\abstractSpecification;

/**
 * This file is part of the <Trigger> project.
 * True if only one condition is true
 *
 * @category   Trigger
 * @package    Specification
 * @subpackage Object
 */
class XorSpec extends abstractSpecification implements InterfaceSpecification 
{
    private $specification1;
    private $specification2;

    function __construct(InterfaceSpecification $specification1, InterfaceSpecification $specification2) {
        $this->specification1 = $specification1;
        $this->specification2 = $specification2;
    }

    public function isSatisfiedBy($object) {
        return (
                ($this->specification1->isSatisfiedBy($object)
                    &&  !$this->specification2->isSatisfiedBy($object)
                )
                ||
                (!$this->specification1->isSatisfiedBy($object)
                    &&  $this->specification2->isSatisfiedBy($object)                    
                )
           )
        ;
    }
}
