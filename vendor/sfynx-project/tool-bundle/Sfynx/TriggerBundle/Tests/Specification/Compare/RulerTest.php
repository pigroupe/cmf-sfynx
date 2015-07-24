<?php

namespace Sfynx\TriggerBundle\Tests\Specification\Compare;

use Sfynx\TriggerBundle\Tests\Specification\Compare\SpecTest;
use Sfynx\TriggerBundle\Specification\Compare\EqualToSpec;

class RulerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider truthTableTwo
     */
    public function testDeMorgan($p, $q)
    {
       
        $isOk1 = (new SpecTest());
        $isOk1
        ->andSpec(
            $isOk1->equalToSpecRun($p, true)
        )
        ->andSpec(
            $isOk1->equalToSpecRun($q, true)
        )->isSatisfiedBy('coincoin');          
        
        
        
        $isOk2 = (new SpecTest());
        $isOk2
        ->andSpec(
            $isOk2->equalToSpecRun($p, true)
        )
        ->andSpec(
            $isOk2->equalToSpecRun($p, true)
        )->isSatisfiedBy('pouetpouet');         
        
        
        $this->assertEquals($isOk1, $isOk2);
        
        
        
//        $this->assertEquals(
//            $rb->create(
//                $rb->logicalNot(
//                    $rb->logicalAnd(
//                        $rb['p']->equalTo(true),
//                        $rb['q']->equalTo(true)
//                    )
//                )
//            )->evaluate($context),
//            $rb->create(
//                $rb->logicalOr(
//                    $rb->logicalNot(
//                        $rb['p']->equalTo(true)
//                    ),
//                    $rb->logicalNot(
//                        $rb['q']->equalTo(true)
//                    )
//                )
//            )->evaluate($context)
//        );
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