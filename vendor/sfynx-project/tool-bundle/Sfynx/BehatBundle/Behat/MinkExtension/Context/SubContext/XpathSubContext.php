<?php
/**
 * This file is part of the <Behat> project.
 *
 * @category   Behat
 * @package    Mink
 * @subpackage SubContext
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
namespace Sfynx\BehatBundle\Behat\MinkExtension\Context\SubContext;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * Mink context for Behat BDD tool.
 * Provides Mink integration and base step definitions with additional options.
 * 
 * @category   Behat
 * @package    Mink
 * @subpackage SubContext
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-03-02
 */
class XpathSubContext extends RawMinkContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters
     */
    public function __construct()
    {
    }

    /**
     * @param string $radioLabel
     *
     * @throws ElementNotFoundException
     * @return void
     * @Given /^I select the "([^"]*)" radio button$/
     */
    public function iSelectTheRadioButton($radioLabel)
    {
        $radioButton = $this->getSession()->getPage()->findField($radioLabel);
        if (null === $radioButton) {
            throw new ElementNotFoundException($this->getSession(), 'form field', 'id|name|label|value', $radioLabel);
        }
        $value = $radioButton->getAttribute('value');
        $this->getSession()->getDriver()->click($radioButton->getXPath());
    }
    
    /**
     * @When I click on number :num
     */
    public function iClickOnNumber($num)
    {
        $this->clickOn('#num-'.$num);
    }    
    
   /**
    * Click some text
    * <code>
    *   <ul>
    *     <li><label><span>Refine Heading</span></label>
    *       <ul>
    *          <li><label>Refine criteria</label></li>
    *       </ul>
    *     </li>
    *   </ul>
    * </code>
    *
    * @When /^I click on the text "([^"]*)"$/
    */
    public function iClickOnTheText($text)
    {
        $session = $this->getSession();
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('xpath', '*//*[text()="'. $text .'"]')
        );
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
        }
 
        $element->click();
 
    }      
    
    /**
     * Click on element CSS with index name
     * 
     * css=a[href='#id3']
     * css=span#firstChild + span
     * 
     * @When /^(?:|I )click on "(?P<id>(?:[^"]|\\")*)"$/
     */
    public function clickOn($element)
    {
        $this->assertSession()->elementExists('css', $element)->click();
    }        
    
    /**
     * Click on the element with the provided xpath query
     * exemple:
     *      Given I click on the element with xpath "//a[@id='14']"
     *      Given I click on the element with xpath "//label[text()='My awesome test']"
     *      Given I click on the element with xpath "//div[@id='myid']//div[@class='myclass']//p[text()='found my text']"
     *      Given I click on the element with xpath "//input[@type='radio' and @checked='checked']//following-sibling::label[contains(text(), '$option')]"
     *      Given I click on the element with xpath "//div[contains(., '$identifier') and @class[contains(.,'form-type-radio')]]"
     *      Given I click on the element with xpath "//*[contains(@id,'tabs')]//form[@class='myform']//div[@id='piapp_adminbundle_pagetype']//fieldset//div[4]//button"
     * 
     * 
     * xpath=//img[@alt=’The image alt text’]
     * xpath=//table[@id=’table1’]//tr[4]/td[2]
     * xpath=//a[contains(@href,’#id1’)]
     * xpath=//a[contains(@href,’#id1’)]/@class
     * xpath=(//table[@class=’stylee’])//th[text()=’theHeaderText’]/../td
     * xpath=//input[@name=’name2’ and @value=’yes’]
     * xpath=//*[text()=”right”]
     * xpath=//html//*[@data-search-id='images']
     * 
     * 
     * 
     * @When /^I click on the element with xpath "([^"]*)"$/
     */
    public function iClickOnTheElementWithXpath($xpath)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('xpath', $xpath)
        ); 
        //print_r($element->getHtml());exit;
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
        //print_r($element->getHtml());exit;
        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS Selector: "%s"', $cssSelector));
        }
        // ok, let's click on it
        $element->click(); 
    }   
    
    /**
     * @Then /^I should see "([^"]+)" on page headline$/
     */
    public function iShouldSeeTextOnPageHeadline($text) {
        assertNotNull($this->find('xpath', '//h1[contains(., "'.$text.'")]'), "Text '$text' was not found on page headline");
    }  
    
    /**
     * This is a simple shortcut for
     * $this->session->getPage()->getSelectorsHandler()->xpathLiteral()
     *
     * @param string $text
     */
    public function literal($text)
    {
        return $this->getSession()->getSelectorsHandler()->xpathLiteral($text);
    }
    
    /**
     * Find all elements that match XPath
     *
     * @param string $xpath XPath to find the elements
     *
     * @return \Behat\Mink\Element\NodeElement[] Array with NodeEelments that match
     */
    public function findXpath($xpath)
    {
        return $this->getSession()->getPage()->findAll('xpath', $xpath);
    }
    
    /**
     * Make XPath for a specific element/object using Behat selectors
     *
     * @param string $element Type of element for the XPath
     * @param string $search String to search
     *
     * @return string XPath for the element/object
     */
    public function makeElementXpath($element, $search)
    {
        $selectorsHandler = $this->getSession()->getSelectorsHandler();
        $literal = $selectorsHandler->xpathLiteral($search);
        return $selectorsHandler
            ->getSelector('named')
            ->translateToXPath(array($element, $literal));
    }
    
    /**
     * Find page objects/elements
     *
     * @param string $element Object type
     * @param string $search Text to search for
     * @param null|string $prefix XPath prefix if needed
     *
     * @return \Behat\Mink\Element\NodeElement[] Array with NodeEelments that match
     */
    public function findObjects($element, $search, $prefix = null)
    {
        $xpath = $this->mergePrefixToXpath(
            $prefix,
            $this->makeElementXpath($element, $search)
        );
        return $this->findXpath($xpath);
    }
    
    /**
     * Default method to find link elements
     *
     * @param string $search Text to search for
     * @param null|string $prefix XPath prefix if needed
     *
     * @return \Behat\Mink\Element\NodeElement[] Array with NodeEelments that matched
     */
    public function findLinks($search, $prefix = null)
    {
        return $this->findObjects('link', $search, $prefix);
    }
    
    /**
     * Default method to find button elements
     *
     * @param string $search Text to search for
     * @param null|string $prefix XPath prefix if needed
     *
     * @return \Behat\Mink\Element\NodeElement[] Array with NodeEelments that matched
     */
    public function findButtons($search, $prefix = null)
    {
        return $this->findObjects('button', $search, $prefix);
    }
    /**
     * Default method to find fieldset elements
     *
     * @param string $search Text to search for
     * @param null|string $prefix XPath prefix if needed
     *
     * @return \Behat\Mink\Element\NodeElement[] Array with NodeEelments that matched
     */
    public function findFieldsetss($search, $prefix = null)
    {
        return $this->findObjects('fieldset', $search, $prefix);
    }
    
    /**
     * Default method to find field elements
     *
     * @param string $search Text to search for
     * @param null|string $prefix XPath prefix if needed
     *
     * @return \Behat\Mink\Element\NodeElement[] Array with NodeEelments that matched
     */
    public function findFields($search, $prefix = null)
    {
        return $this->findObjects('field', $search, $prefix);
    }
    
    /**
     * Default method to find content elements
     *
     * @param string $search Text to search for
     * @param null|string $prefix XPath prefix if needed
     *
     * @return \Behat\Mink\Element\NodeElement[] Array with NodeEelments that matched
     */
    public function findContents($search, $prefix = null)
    {
        return $this->findObjects('content', $search, $prefix);
    }
    
    /**
     * Default method to find select elements
     *
     * @param string $search Text to search for
     * @param null|string $prefix XPath prefix if needed
     *
     * @return \Behat\Mink\Element\NodeElement[] Array with NodeEelments that matched
     */
    public function findSelects($search, $prefix = null)
    {
        return $this->findObjects('select', $search, $prefix);
    }
    
    /**
     * Default method to find checkbox elements
     *
     * @param string $search Text to search for
     * @param null|string $prefix XPath prefix if needed
     *
     * @return \Behat\Mink\Element\NodeElement[] Array with NodeEelments that matched
     */
    public function findCheckboxs($search, $prefix = null)
    {
        return $this->findObjects('checkbox', $search, $prefix);
    }
    
    /**
     * Default method to find radio elements
     *
     * @param string $search Text to search for
     * @param null|string $prefix XPath prefix if needed
     *
     * @return \Behat\Mink\Element\NodeElement[] Array with NodeEelments that matched
     */
    public function findRadios($search, $prefix = null)
    {
        return $this->findObjects('radio', $search, $prefix);
    }
    
    /**
     * Default method to find file elements
     *
     * @param string $search Text to search for
     * @param null|string $prefix XPath prefix if needed
     *
     * @return \Behat\Mink\Element\NodeElement[] Array with NodeEelments that matched
     */
    public function findFiles($search, $prefix = null)
    {
        return $this->findObjects('file', $search, $prefix);
    }
    
    /**
     * Default method to find option elements
     *
     * @param string $search Text to search for
     * @param null|string $prefix XPath prefix if needed
     *
     * @return \Behat\Mink\Element\NodeElement[] Array with NodeEelments that matched
     */
    public function findOptions( $search, $prefix = null)
    {
        return $this->findObjects('option', $search, $prefix);
    }
    
    /**
     * Default method to find table elements
     *
     * @param string $search Text to search for
     * @param null|string $prefix XPath prefix if needed
     *
     * @return \Behat\Mink\Element\NodeElement[] Array with NodeEelments that matched
     */
    public function findTables($search, $prefix = null)
    {
        return $this->findObjects('table', $search, $prefix);
    }
    
    /**
     * Merge/inject prefix into multiple case XPath
     *
     * ex:
     *   $xpath = '//h1 | //h2';
     *   $prefix = '//article';
     *   return "//article/.//h1 | //article/.//h2"
     *
     * @param string $prefix XPath prefix
     * @param string $xpath Complete XPath
     *
     * @return string XPath with prefixes (or original if no prefix passed)
     */
    public function mergePrefixToXpath($prefix, $xpath)
    {
        if (empty($prefix))
        {
            return $xpath;
        }
        if ($prefix[strlen($prefix) - 1] !== '/')
        {
            $prefix .= '/';
        }
        return $prefix . implode( "| $prefix", explode( '|', $xpath ) );
    }    
}