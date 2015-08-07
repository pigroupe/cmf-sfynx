<?php

namespace Sfynx\SpecificationBundle\Tests\Specification\Compare;

use Sfynx\SpecificationBundle\Specification\abstractSpecification;

/**
 * This file is part of the <Trigger> project.
 * 
 * @category   Trigger
 * @package    Specification
 * @subpackage Test
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
