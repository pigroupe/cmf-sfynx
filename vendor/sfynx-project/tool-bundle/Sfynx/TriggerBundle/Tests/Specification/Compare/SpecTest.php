<?php

namespace Sfynx\TriggerBundle\Tests\Specification\Compare;

use Sfynx\TriggerBundle\Specification\Builder\InterfaceCompare;
use Sfynx\TriggerBundle\Specification\abstractSpecification;

/**
 * This file is part of the <Trigger> project.
 * true if $a > $b
 * 
 * @category   Trigger
 * @package    Specification
 * @subpackage Object
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SpecTest extends abstractSpecification {

    public function isSatisfiedBy($object) {
        if (strlen($object) >= 3) {
            return true;
        } else {
            return false;
        }
    }
}
