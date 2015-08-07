<?php

namespace Sfynx\SpecificationBundle\Tests\Specification\Compare;

use Sfynx\SpecificationBundle\Tests\Specification\Compare\SpecTest;

/**
 * This file is part of the <Trigger> project.
 * 
 * @category   Trigger
 * @package    Specification
 * @subpackage Test
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class RulerTest extends \PHPUnit_Framework_TestCase
{
   /**
     * Logical test:  (A) === (NOT(NOT(A)))
     * 
     * @dataProvider truthTableOne
     */
    public function testDoubleNegation($p)
    {
        $isOk1 = (new SpecTest());
        $val1 = $isOk1->EqualToSpec($p, true)
        ->isSatisfiedBy('coincoin'); 

        
        $isOk2 = (new SpecTest());
        $val2 = $isOk2->NotSpec(
            $isOk2->NotSpec($isOk2->EqualToSpec($p, true))
        )
        ->isSatisfiedBy('pouetpouet');
        
        $this->assertEquals($val1, $val2);
        
    }
    
    /**
     * Logical test:  (A) === (A OU A)
     * 
     * @dataProvider truthTableOne
     */
    public function testTautology($p)
    {
        $isOk1 = (new SpecTest());
        $val1 = $isOk1->EqualToSpec($p, true)
        ->isSatisfiedBy('coincoin'); 

        
        $isOk2 = (new SpecTest());
        $val2 = $isOk2
        ->AndSpec(
            $isOk2->EqualToSpec($p, true)
        )
        ->OrSpec(
            $isOk2->EqualToSpec($p, true)
        )
        ->isSatisfiedBy('pouetpouet');
        
        $this->assertEquals($val1, $val2);
    }
    
    /**
     * Logical test:  (A) === (A AND A)
     * 
     * @dataProvider truthTableOne
     */
    public function testTautologyTwo($p)
    {
        $isOk1 = (new SpecTest());
        $val1 = $isOk1->EqualToSpec($p, true)
        ->isSatisfiedBy('coincoin'); 

        
        $isOk2 = (new SpecTest());
        $val2 = $isOk2
        ->AndSpec(
            $isOk2->EqualToSpec($p, true)
        )
        ->AndSpec(
            $isOk2->EqualToSpec($p, true)
        )
        ->isSatisfiedBy('pouetpouet');
        
        $this->assertEquals($val1, $val2);
    }
    
    /**
     * Logical test:  (A OR NOT(A)) === true
     * 
     * @dataProvider truthTableOne
     */
    public function testExcludedMiddle($p)
    {
        $isOk2 = (new SpecTest());
        $val2 = $isOk2
        ->AndSpec(
            $isOk2->EqualToSpec($p, true)
        )
        ->OrSpec(
            $isOk2->NotSpec($isOk2->EqualToSpec($p, true))
        )
        ->isSatisfiedBy('pouetpouet');
        
        $this->assertEquals(true, $val2);
    }
    
    /**
     * Logical test:  NOT(A AND NOT(A)) === true
     * 
     * @dataProvider truthTableOne
     */
    public function testNonContradiction($p)
    {
        $isOk2 = (new SpecTest());
        $val2 = $isOk2
        ->NotSpec(
            $isOk2->AndSpec(
                $isOk2->EqualToSpec($p, true)
            )
            ->AndSpec(
                $isOk2->NotSpec($isOk2->EqualToSpec($p, true))
            )
        )
        ->isSatisfiedBy('pouetpouet');
        
        $this->assertEquals(true, $val2);
    }    
    
    /**
     * Logical test:  NOT(A AND B) === NOT(A) OR NOT(B)
     * 
     * @dataProvider truthTableTwo
     */
    public function testDeMorgan($p, $q)
    {       
        $isOk1 = (new SpecTest());
        $val1 = $isOk1->NotSpec(
            $isOk1->AndSpec(
                $isOk1->EqualToSpec($p, true)
            )
            ->AndSpec(
                $isOk1->EqualToSpec($q, true)
            )        
        )
        ->isSatisfiedBy('coincoin'); 

        
        $isOk2 = (new SpecTest());
        $val2 = $isOk2->AndSpec(
            $isOk2->NotSpec($isOk2->EqualToSpec($p, true))
        )
        ->OrSpec(
            $isOk2->NotSpec($isOk2->EqualToSpec($q, true))
        )
        ->isSatisfiedBy('pouetpouet');
        
        $this->assertEquals($val1, $val2);
    }
    
    /**
     * Logical test:  NOT(A OR B) === NOT(A) AND NOT(B)
     * 
     * @dataProvider truthTableTwo
     */
    public function testDeMorganTwo($p, $q)
    {
        $isOk1 = (new SpecTest());
        $val1 = $isOk1->NotSpec(
            $isOk1->AndSpec(
                $isOk1->EqualToSpec($p, true)
            )
            ->OrSpec(
                $isOk1->EqualToSpec($q, true)
            )        
        )
        ->isSatisfiedBy('coincoin'); 

        
        $isOk2 = (new SpecTest());
        $val2 = $isOk2->AndSpec(
            $isOk2->NotSpec($isOk2->EqualToSpec($p, true))
        )
        ->AndSpec(
            $isOk2->NotSpec($isOk2->EqualToSpec($q, true))
        )
        ->isSatisfiedBy('pouetpouet');
        
        $this->assertEquals($val1, $val2);
    }
    
    /**
     * Logical test:  (A OR B) === (B OR A)
     * 
     * @dataProvider truthTableTwo
     */
    public function testCommutation($p, $q)
    {
        $isOk1 = (new SpecTest());
        $val1 = $isOk1->AndSpec(
            $isOk1->EqualToSpec($p, true)
        )
        ->OrSpec(
            $isOk1->EqualToSpec($q, true)
        )        
        ->isSatisfiedBy('coincoin'); 

        
        $isOk2 = (new SpecTest());
        $val2 = $isOk2->AndSpec(
            $isOk2->EqualToSpec($q, true)
        )
        ->OrSpec(
            $isOk2->EqualToSpec($p, true)
        )        
        ->isSatisfiedBy('pouetpouet'); 
        
        $this->assertEquals($val1, $val2);
    }
    
    /**
     * Logical test:  (A AND B) === (B AND A)
     * 
     * @dataProvider truthTableTwo
     */
    public function testCommutationTwo($p, $q)
    {
        $isOk1 = (new SpecTest());
        $val1 = $isOk1->AndSpec(
            $isOk1->EqualToSpec($p, true)
        )
        ->AndSpec(
            $isOk1->EqualToSpec($q, true)
        )        
        ->isSatisfiedBy('coincoin'); 

        
        $isOk2 = (new SpecTest());
        $val2 = $isOk2->AndSpec(
            $isOk2->EqualToSpec($q, true)
        )
        ->AndSpec(
            $isOk2->EqualToSpec($p, true)
        )        
        ->isSatisfiedBy('pouetpouet'); 
        
        $this->assertEquals($val1, $val2);
    }
    
    /**
     * Logical test:  (A OR (B OR C)) === ((A OR B) OR C)
     * 
     * @dataProvider truthTableThree
     */
    public function testAssociation($p, $q, $r)
    {        
        $isOk1 = (new SpecTest());
        $val1 = $isOk1->AndSpec(
            $isOk1->EqualToSpec($p, true)
        )
        ->OrSpec(
            $isOk1->AndSpec(
                $isOk1->EqualToSpec($q, true)
            )
            ->OrSpec(
                $isOk1->EqualToSpec($r, true)
            )
        )        
        ->isSatisfiedBy('coincoin'); 

        
        $isOk2 = (new SpecTest());
        $val2 = $isOk2->AndSpec(
            $isOk2->AndSpec(
                $isOk2->EqualToSpec($p, true)
            )
            ->OrSpec(
                $isOk2->EqualToSpec($q, true)
            )
        )
        ->OrSpec(
            $isOk2->EqualToSpec($r, true)
        )         
        ->isSatisfiedBy('pouetpouet'); 
        
        $this->assertEquals($val1, $val2);
    }
    
    /**
     * Logical test:  (A AND (B AND C)) === ((A AND B) AND C)
     * 
     * @dataProvider truthTableThree
     */
    public function testAssociationTwo($p, $q, $r)
    {
        $isOk1 = (new SpecTest());
        $val1 = $isOk1->AndSpec(
            $isOk1->EqualToSpec($p, true)
        )
        ->AndSpec(
            $isOk1->AndSpec(
                $isOk1->EqualToSpec($q, true)
            )
            ->AndSpec(
                $isOk1->EqualToSpec($r, true)
            )
        )        
        ->isSatisfiedBy('coincoin'); 

        
        $isOk2 = (new SpecTest());
        $val2 = $isOk2->AndSpec(
            $isOk2->AndSpec(
                $isOk2->EqualToSpec($p, true)
            )
            ->AndSpec(
                $isOk2->EqualToSpec($q, true)
            )
        )
        ->AndSpec(
            $isOk2->EqualToSpec($r, true)
        )         
        ->isSatisfiedBy('pouetpouet'); 
        
        $this->assertEquals($val1, $val2);
    }
    
    /**
     * Logical test:  (A AND (B OR C)) === ((A AND B) OR (A AND C))
     * 
     * @dataProvider truthTableThree
     */
    public function testDistribution($p, $q, $r)
    {
        $isOk1 = (new SpecTest());
        $val1 = $isOk1->AndSpec(
            $isOk1->EqualToSpec($p, true)
        )
        ->AndSpec(
            $isOk1->AndSpec(
                $isOk1->EqualToSpec($q, true)
            )
            ->OrSpec(
                $isOk1->EqualToSpec($r, true)
            )
        )        
        ->isSatisfiedBy('coincoin'); 

        
        $isOk2 = (new SpecTest());
        $val2 = $isOk2->AndSpec(
            $isOk2->AndSpec(
                $isOk2->EqualToSpec($p, true)
            )
            ->AndSpec(
                $isOk2->EqualToSpec($q, true)
            )
        )
        ->OrSpec(
            $isOk2->AndSpec(
                $isOk2->EqualToSpec($p, true)
            )
            ->AndSpec(
                $isOk2->EqualToSpec($r, true)
            )
        )        
        ->isSatisfiedBy('pouetpouet'); 
        
        $this->assertEquals($val1, $val2);
    }
    
    /**
     * Logical test:  (A OR (B AND C)) === ((A OR B) AND (A OR C))
     * 
     * @dataProvider truthTableThree
     */
    public function testDistributionTwo($p, $q, $r)
    {
        $isOk1 = (new SpecTest());
        $val1 = $isOk1->AndSpec(
            $isOk1->EqualToSpec($p, true)
        )
        ->OrSpec(
            $isOk1->AndSpec(
                $isOk1->EqualToSpec($q, true)
            )
            ->AndSpec(
                $isOk1->EqualToSpec($r, true)
            )
        )        
        ->isSatisfiedBy('coincoin'); 

        
        $isOk2 = (new SpecTest());
        $val2 = $isOk2->AndSpec(
            $isOk2->AndSpec(
                $isOk2->EqualToSpec($p, true)
            )
            ->OrSpec(
                $isOk2->EqualToSpec($q, true)
            )
        )
        ->AndSpec(
            $isOk2->AndSpec(
                $isOk2->EqualToSpec($p, true)
            )
            ->OrSpec(
                $isOk2->EqualToSpec($r, true)
            )
        )        
        ->isSatisfiedBy('pouetpouet'); 
        
        $this->assertEquals($val1, $val2);
    }
    
    public function truthTableOne()
    {
        return array(
            array(true),
            array(false),
        );
    }    
    
    public function truthTableTwo()
    {
        return array(
            array(true,  true),
            array(true,  false),
            array(false, true),
            array(false, false),
        );
    }    
    
    public function truthTableThree()
    {
        return array(
            array(true,  true,  true),
            array(true,  true,  false),
            array(true,  false, true),
            array(true,  false, false),
            array(false, true,  true),
            array(false, true,  false),
            array(false, false, true),
            array(false, false, false),
        );
    }    
}
