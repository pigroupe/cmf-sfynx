<?php
/**
 * This file is part of the <Behat> project.
 *
 * @category   Behat
 * @package    Mink
 * @subpackage Context
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-03-02
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\BehatBundle\Behat\MinkExtension\Context;

use Behat\MinkExtension\Context\MinkContext as BaseMinkContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Mink\Exception\UnsupportedDriverActionException,
    Behat\Mink\Exception\ExpectationException;
use Behat\Symfony2Extension\Driver\KernelDriver;

/**
 * Mink context for Behat BDD tool.
 * Provides Mink integration and base step definitions with additional options.
 * 
 * @category   Behat
 * @package    Mink
 * @subpackage Context
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-03-02
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
    public function __construct() 
    {
        $this->forTheServer(self::$options['server'], self::$options['locale']);
    }
    
    public function getSymfonyProfile()
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof KernelDriver) {
            throw new UnsupportedDriverActionException(
                'You need to tag the scenario with '.
                '"@mink:symfony2". Using the profiler is not '.
                'supported by %s', $driver
            );
        }

        $profile = $driver->getClient()->getProfile();
        if (false === $profile) {
            throw new \RuntimeException(
                'The profiler is disabled. Activate it by setting '.
                'framework.profiler.only_exceptions to false in '.
                'your config'
            );
        }

        return $profile;
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
        if (count(self::$allowed) >= 1) {
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
    
    /**
     * Click on the element with the provided xpath query
     * exemple:
     *      Given I click on the element with xpath "//a[@id='14']"
     * 
     * @When /^I click on the element with xpath "([^"]*)"$/
     */
    public function iClickOnTheElementWithXPath($xpath)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('xpath', $xpath)
        ); // runs the actual query and returns the element
 
        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate XPath: "%s"', $xpath));
        }
        
        // ok, let's click on it
        $element->click();
    }    
    
    /**
     * Click on the element with the provided CSS Selector
     * exemple: Given I click on the element with css selector "a#14"
     *
     * @When /^I click on the element with css selector "([^"]*)"$/
     */
    public function iClickOnTheElementWithCSSSelector($cssSelector)
    {
        $session = $this->getSession();
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('css', $cssSelector) // just changed xpath to css
        );
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS Selector: "%s"', $cssSelector));
        }
        $title = $this->getSession()->getPage()->find('css', 'title')->getText();
 
        $element->click();
 
    }    
}
