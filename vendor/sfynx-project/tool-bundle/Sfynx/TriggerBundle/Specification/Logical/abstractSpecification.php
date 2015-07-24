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

    public function andSpec(InterfaceSpecification $specification) {
        return new AndSpec($this, $specification);
    }

    public function orSpec(InterfaceSpecification $specification) {
        return new OrSpec($this, $specification);
    }

    public function notSpec(InterfaceSpecification $specification) {
        return new NotSpec($specification);
    }
    
    public function xorSpec(InterfaceSpecification $specification) {
        return new XorSpec($this, $specification);
    }    
}
