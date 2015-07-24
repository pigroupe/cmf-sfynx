<?php

namespace Sfynx\TriggerBundle\Specification\Logical;

use Sfynx\TriggerBundle\Specification\Builder\InterfaceSpecification;
use Sfynx\TriggerBundle\Specification\Logical\abstractSpecification;

/**
 * This file is part of the <Trigger> project.
 * True if false and false if true
 * 
 * @category   Trigger
 * @package    Specification
 * @subpackage Object
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class NotSpec extends abstractSpecification implements InterfaceSpecification {

    private $specification;

    public function __construct(InterfaceSpecification $specification) {
        $this->specification = $specification;
    }

    public function isSatisfiedBy($object) {
        return !$this->specification->isSatisfiedBy($object);
    }
}
