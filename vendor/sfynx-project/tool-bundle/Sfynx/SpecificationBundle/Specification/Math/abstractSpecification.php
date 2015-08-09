<?php

namespace Sfynx\SpecificationBundle\Specification\Math;

use Sfynx\SpecificationBundle\Specification\Builder\InterfaceSpecification;

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
    public function __call($name, $arguments)
    {
        $className = '\Sfynx\SpecificationBundle\Specification\Math\\' . $name;        
        if (class_exists($className)) {            
            if (isset($arguments[0]) 
                    && $arguments[0] instanceof  InterfaceSpecification
            ) {
                return new $className($this, $arguments[0]);
            } else {
                return new $className($arguments[0], $arguments[1]);
            }  
            
        } else {
            throw new \Exception('The class does not existed'); 
        }
    }
    
    protected function setValues($specification1, $specification2)
    {
        if ($specification1 instanceof  InterfaceSpecification
                && $specification2 instanceof  InterfaceSpecification)
        {
            $a = $specification1->isSatisfiedBy($object);
            $b = $specification2->isSatisfiedBy($object);
        } else {
            $a = $specification1;
            $b = $specification2;
        }        
        
        return array($a, $b);
    }
}
