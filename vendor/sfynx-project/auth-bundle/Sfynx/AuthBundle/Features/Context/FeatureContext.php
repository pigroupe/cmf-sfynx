<?php

namespace Sfynx\AuthBundle\Features\Context;

//use Behat\Behat\Context\SnippetAcceptingContext;
//use Sfynx\BehatBundle\Behat\MinkExtension\Context\MinkContext;
//use Symfony\Component\HttpFoundation\Session\Session;

use Sfynx\BehatBundle\Behat\MinkExtension\Context\FeatureContext as baseFeatureContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;

/**
 * Defines application features from the specific context.
 * 
 * class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
 */
class FeatureContext extends baseFeatureContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        parent::__construct();
    }
}
