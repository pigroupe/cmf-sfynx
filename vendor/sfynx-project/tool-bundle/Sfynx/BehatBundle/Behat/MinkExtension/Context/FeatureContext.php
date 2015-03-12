<?php

namespace Sfynx\BehatBundle\Behat\MinkExtension\Context;

//use Behat\Behat\Context\SnippetAcceptingContext;
//use Sfynx\BehatBundle\Behat\MinkExtension\Context\MinkContext;
//use Symfony\Component\HttpFoundation\Session\Session;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeFeatureScope;
use Behat\Behat\Hook\Scope\AfterFeatureScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeStepScope;
use Behat\Behat\Hook\Scope\AfterStepScope;

/**
 * Defines application features from the specific context.
 * 
 * class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
 */
class FeatureContext implements SnippetAcceptingContext        
{
    /** @var \Behat\MinkExtension\Context\MinkContext */
    private $minkContext;
    
    /** @var \Sfynx\BehatBundle\Behat\MinkExtension\Context\SubContext\AjaxContext */
    private $ajaxsubcontext;   
    
    /** @var \Sfynx\BehatBundle\Behat\MinkExtension\Context\SubContext\HiddenFieldSubContext */
    private $hiddenfieldsubcontext;  
        
    /** @var \Sfynx\BehatBundle\Behat\MinkExtension\Context\SubContext\RadioButtonSubContext */
    private $radiobuttonsubcontext;  
    
    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        $this->minkContext           = $environment->getContext('Sfynx\BehatBundle\Behat\MinkExtension\Context\MinkContext');
        $this->ajaxsubcontext        = $environment->getContext('Sfynx\BehatBundle\Behat\MinkExtension\Context\SubContext\AjaxContext');
        $this->hiddenfieldsubcontext = $environment->getContext('Sfynx\BehatBundle\Behat\MinkExtension\Context\SubContext\HiddenFieldSubContext');
        $this->radiobuttonsubcontext = $environment->getContext('Sfynx\BehatBundle\Behat\MinkExtension\Context\SubContext\RadioButtonSubContext');
    }   
    
    /** @BeforeFeature */
    public static function setupFeature(BeforeFeatureScope $scope)
    {
    }

    /** @AfterFeature */
    public static function teardownFeature(AfterFeatureScope $scope)
    {
    }    
    
    /** @BeforeScenario */
    public function beforeScenario(BeforeScenarioScope $scope)
    {
    }

    /** @AfterScenario */
    public function afterScenario(AfterScenarioScope $scope)
    {
    }    
    
    /** @BeforeStep */
    public function beforeStep(BeforeStepScope $scope)
    {
    }

    /** @AfterStep */
    public function afterStep(AfterStepScope $scope)
    {
    }
}
