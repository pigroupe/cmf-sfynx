<?php

namespace Sfynx\SpecificationBundle\Specification\Logical;

use Sfynx\SpecificationBundle\Specification\Builder\InterfaceSpecification;
use Sfynx\SpecificationBundle\Specification\Compare\abstractSpecification as CompareSpec;

/**
 * This file is part of the <Trigger> project.
 *
 * @category   Trigger
 * @package    Specification
 * @subpackage Object
 * @abstract
 */
abstract class abstractSpecification extends CompareSpec implements InterfaceSpecification {

    public function AndSpec(InterfaceSpecification $specification) {
        return new AndSpec($this, $specification);
    }

    public function OrSpec(InterfaceSpecification $specification) {
        return new OrSpec($this, $specification);
    }

    public function NotSpec($specification = null) {
        if ($specification instanceof  InterfaceSpecification)
        {
            return new NotSpec($specification);
        } else {
            return new NotSpec($this);
        }   
    }
    
    public function XorSpec(InterfaceSpecification $specification) {
        return new XorSpec($this, $specification);
    }    
}
