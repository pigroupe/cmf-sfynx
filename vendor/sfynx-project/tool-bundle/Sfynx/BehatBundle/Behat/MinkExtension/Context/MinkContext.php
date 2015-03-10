<?php

namespace Sfynx\BehatBundle\Behat\MinkExtension\Context;

use Behat\MinkExtension\Context\MinkContext as BaseMinkContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;
use Sfynx\BehatBundle\Features\Context\SubContext\RadioButtonSubContext;
use Sfynx\BehatBundle\Features\Context\SubContext\AjaxSubContext;

/**
 * Mink context for Behat BDD tool.
 * Provides Mink integration and base step definitions with additional options.
 *
 */
class MinkContext extends BaseMinkContext implements SnippetAcceptingContext, KernelAwareContext
{
    /**
     * Behat additional options
     * 
     * @var array $options
     */
    
    public static $options;
    /**
     * Allowed values for addtional options
     * 
     * @var array $allowed
     */
    public static $allowed;
    
    /**
     * Application Kernel
     * 
     * @var KernelInterface $kernel
     */
    private $kernel;

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $container = $this->kernel->getContainer();
        if (self::$options === null) {
            self::$options = $container->getParameter('behat.options');
        }
        if (self::$allowed === null) {
            self::$allowed = array(
                'servers' => $container->getParameter('behat.servers'),
                'locales' => $container->getParameter('behat.locales')
            );
        }
    }
    
    /**
     * Behat additional options initializer
     */
    public function __construct(array $parameters) {
        $this->forTheServer(self::$options['server'], self::$options['locale']);
        //
        $this->useContext('RadioButtonSubContext', new RadioButtonSubContext($parameters));
        $this->useContext('AjaxSubContext', new AjaxSubContext($parameters));
        
        parent::__construct($parameters);
    }
    
     /**
     * Override method to wait for Ajax requests to finish before continuing
     *
     * @param $text
     */
    public function assertPageContainsText($text)
    {
        //$this->getSession()->wait(10000, '(0 === jQuery.active)');
        parent::assertPageContainsText($text);
    }    
    
    /**
     * Override method to wait for Ajax requests to finish before continuing
     * 
     * @param $text
     */
    public function assertResponseContains($text)
    {
        //$this->getSession()->wait(10000, '(typeof(jQuery)=="undefined" || (0 === jQuery.active && 0 === jQuery(\':animated\').length))');
        //$this->getSession()->wait(10000, '(0 === jQuery.active)');
        parent::assertResponseContains($text);
    }    
    
    /**
     * Log with a role
     * 
     * @Given /^(?:|I am )logged as "(?P<role>(?:[^"]|\\")*)"$/
     */
    public function logAs($role)
    {
        switch ($role) {
            case 'super_admin':
                break;
            case 'admin':
                break;
            case 'user':
                break;
        }
    }
    
    /**
     * @When I wait for :time seconds
     */
    public function iWaitForMessageDisplay($time)
    {
        $this->getSession()->wait($time * 1000);
    }
    
    /**
     * Fills in form field with specified id|name|label|value.
     *
     * @When /^(?:|I )click on mist button"(?P<field>(?:[^"]|\\")*)" with "(?P<value>(?:[^"]|\\")*)"$/
     * @When /^(?:|I )click on mist button"(?P<field>(?:[^"]|\\")*)" with:$/
     * @When /^(?:|I )click on mist button"(?P<value>(?:[^"]|\\")*)" for "(?P<field>(?:[^"]|\\")*)"$/
     */
    public function fillFieldMist($field, $value)
    {
        $this->assertSession()->elementExists($field, $value)->click();
    }
    
    /**
     * Using a specific server and locale
     * 
     * @Given /^for the server "(?P<server>(?:[^"]|\\")*)"(?:| with locale "(?P<locale>(?:[^"]|\\")*)")$/
     */
    public function forTheServer($server = null, $locale = null)
    {   
        if (!in_array($server, self::$allowed['servers']) && !empty($server)) {
            throw new \Exception('Website server "'.$server.'" not found.');
        } else {
            $server = self::$options['server'];
        }
        if ($locale !== '' && !in_array($locale, self::$allowed['locales']) && !empty($locale)) {
            throw new \Exception('Website locale "'.$locale.'" not found.');
        } elseif ($locale == '') {
            $locale = self::$options['locale'];
        }
        
        $baseUrl = 'http://bitume.'.strtolower($server).'.dev';
        $this->setMinkParameter('base_url', strtr($baseUrl, array(' ', '')));
    }
    
    /**
     * Enable or disable JS
     * 
     * @Given /^With(?P<suffix>(?:|out)) Javascript$/
     */
    public function withJavascript($suffix)
    {
        if ($suffix == 'out') {
            // Use Goutte (default: Selenium)
        }
    }
    
    /**
     * Click on element CSS with index name
     * 
     * @When /^(?:|I )click on "(?P<id>(?:[^"]|\\")*)"$/
     */
    public function clickOn($element)
    {
        $this->assertSession()->elementExists('css', $element)->click();
    }
    
    /**
     * Checks, that element with specified CSS is visible on page.
     *
     * @Then /^(?:|The )"(?P<element>[^"]*)" element (should be|is) visible$/
     */
    public function assertElementVisible($element)
    {
        if (!$this->assertSession()->elementExists('css', $element)->isVisible()) {
            throw new \Exception('Element "'.$element.'" not visible.');
        }
    }
    
    /**
     * Checks, that element with specified CSS is not visible on page.
     *
     * @Then /^(?:|The )"(?P<element>[^"]*)" element (should not be|is not) visible$/
     */
    public function assertElementNotVisible($element)
    {
        if ($this->assertSession()->elementExists('css', $element)->isVisible()) {
            throw new \Exception('Element "'.$element.'" visible.');
        }
    }
    
    /**
     * Checks, that element children with specified CSS are on page.
     * 
     * @param string $element
     * @param array $children
     */
    public function assertElementChildrenOnPage($element, $children = array())
    {
        foreach ($children as $child) {
            $this->assertElementOnPage($element . ' ' . $child);
        }
    }
    
    /**
     * Checks, that element children with specified CSS are not on page.
     * 
     * @param string $element
     * @param array $children
     */
    public function assertElementChildrenNotOnPage($element, $children = array())
    {
        foreach ($children as $child) {
            $this->assertElementNotOnPage($element . ' ' . $child);
        }
    }
    
    /**
     * Checks, that element childrens with specified CSS are visible on page.
     * 
     * @param string $element
     * @param array $childrens
     */
    public function assertElementChildrensVisible($element, $childrens = array())
    {
        foreach ($childrens as $children) {
            $this->assertElementVisible($element.' '.$children);
        }
    }
    
    /**
     * Checks, that element childrens with specified CSS are not visible on page.
     * 
     * @param string $element
     * @param array $childrens
     */
    public function assertElementChildrensNotVisible($element, $childrens = array())
    {
        foreach ($childrens as $children) {
            $this->assertElementNotVisible($element.' '.$children);
        }
    }
    
    /**
     * Check an object parameter existance
     *
     * @Then /^(?:|The )"(?P<property>[^"]*)" property should exists$/
     */
    public function assertPropertyExists($property, $subject = null)
    {
        $object = null;
        switch (gettype($subject)) {
            case 'object':
                $object = $subject;
                break;
            case 'array':
                $object = json_decode(json_encode($subject), false);
                break;
            case 'NULL':
                $subject = $this->getSession()->getPage()->getText();
            case 'string':
                $object = json_decode($subject);
                break;
            default:
                throw new \Exception('Object format not supported.');
        }
        if (!property_exists($object, $property)) {
            throw new \Exception('Object property not found.');
        }
        return $object->$property;
    }
    
    /**
     * {@inheritdoc}
     */
    public function visit($page)
    {
        if ($this->getMinkParameter('base_url') === null) {
            $this->forTheServer(self::$options['server'], self::$options['locale']);
        }
        $this->visitPath($page);
    }
    
    /**
     * @When I click on number :num
     */
    public function iClickOnNumber($num)
    {
        $this->clickOn('#num-'.$num);
    }
    
    /**
     * Presses button with specified id|name|title|alt|value.
     *
     * @When /^(?:|I )press link "(?P<a>(?:[^"]|\\")*)"$/
     */
    public function pressLinkButton($button)
    {
        $this->getSession()->getPage()->find('xpath', '//label[text()="Réinitialiser mon mot de passe"]');
    }
}
