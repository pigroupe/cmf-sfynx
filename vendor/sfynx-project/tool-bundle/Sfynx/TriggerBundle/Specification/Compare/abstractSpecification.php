<?php

namespace Sfynx\TriggerBundle\Specification\Compare;

use Sfynx\TriggerBundle\Specification\Builder\InterfaceSpecification;

/**
 * This file is part of the <Trigger> project.
 *
 * @category   Trigger
 * @package    Specification
 * @subpackage Object
 * @abstract
 */
abstract class abstractSpecification implements InterfaceSpecification 
{
    /* true if $a > $b */
    public function greaterThanSpec(InterfaceSpecification $specification) {
        return new GreaterThanSpec($this, $specification);
    }
    
    /* true if $a >= $b */
    public function greaterThanOrEqualToSpec(InterfaceSpecification $specification) {
        return new GreaterThanOrEqualToSpec($this, $specification);
    }
    
    /* true if $a < $b */
    public function lessThanSpec(InterfaceSpecification $specification) {
        return new LessThanSpec($this, $specification);
    }
    
    /* true if $a <= $b */
    public function lessThanOrEqualToSpec(InterfaceSpecification $specification) {
        return new LessThanOrEqualToSpec($this, $specification);
    }
    
    /* true if $a == $b */
    public function equalToSpec(InterfaceSpecification $specification) {
        return new EqualToSpec($this, $specification);
    }
    public function equalToSpecRun($a, $b) {
        return new EqualToSpec($a, $b);
    }    
    
    /* true if $a != $b */
    public function notEqualToSpec(InterfaceSpecification $specification) {
        return new NotEqualToSpec($this, $specification);
    }
    
    /* true if strpos($b, $a) !== false */
    public function stringContainsSpec(InterfaceSpecification $specification) {
        return new StringContainsSpec($this, $specification);
    }
    
    /* true if strpos($b, $a) === false */
    public function stringDoesNotContainSpec(InterfaceSpecification $specification) {
        return new StringDoesNotContainSpec($this, $specification);
    }
    
    /* true if stripos($b, $a) !== false */
    public function stringContainsInsensitiveSpec(InterfaceSpecification $specification) {
        return new StringContainsInsensitiveSpec($this, $specification);
    }
    
    /* true if stripos($b, $a) === false */
    public function startsWithSpec(InterfaceSpecification $specification) {
        return new StartsWithSpec($this, $specification);
    }
    
    /* true if strpos($b, $a) === 0 */
    public function startsWithInsensitiveSpec(InterfaceSpecification $specification) {
        return new StartsWithInsensitiveSpec($this, $specification);
    }
    
    /* true if stripos($b, $a) === 0 */
    public function endsWithSpec(InterfaceSpecification $specification) {
        return new EndsWithSpec($this, $specification);
    }
    
    /* true if strpos($b, $a) === len($a) - len($b) */
    public function endsWithInsensitiveSpec(InterfaceSpecification $specification) {
        return new EndsWithInsensitiveSpec($this, $specification);
    }
    
    /* t true if $a === $b */
    public function sameAsSpec(InterfaceSpecification $specification) {
        return new SameAsSpec($this, $specification);
    }
    
    /* true if $a !== $b */
    public function notSameAsSpec(InterfaceSpecification $specification) {
        return new NotSameAsSpec($this, $specification);
    }
}
