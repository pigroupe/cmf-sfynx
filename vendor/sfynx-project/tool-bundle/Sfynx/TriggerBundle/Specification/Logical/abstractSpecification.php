<?php

namespace Sfynx\TriggerBundle\Specification\Logical;

use Sfynx\TriggerBundle\Specification\Builder\InterfaceSpecification;
use Sfynx\TriggerBundle\Specification\Compare\abstractSpecification as CompareSpec;

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
