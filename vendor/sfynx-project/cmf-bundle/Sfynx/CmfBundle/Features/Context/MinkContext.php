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
namespace Sfynx\CmfBundle\Features\Context;

use Behat\MinkExtension\Context\RawMinkContext;

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
class MinkContext extends RawMinkContext
{
    
    private static $container = array();
    
    /**
     * @Then I register the new page
     */
    public function iRegisterTheNewPage()
    {
        static::$container['newurl'] = $this->getSession()->getCurrentUrl();
    }
    
    /**
     * @Given I go to the new page
     */
    public function iGoToTheNewPage()
    {
        $this->visitPath(static::$container['newurl']);
    }
    
    /**
     * @Then I click on the main menu
     */
    public function iClickOnTheMainMenu()
    {
        $this->assertSession()->elementExists('css', ".menu-xp")->click();
        $this->getSession()->wait(2 * 1000);
    }     
    
    /**
     * @Then I click to copy the page
     */
    public function iClickToCopyThePage()
    {
        $this->assertSession()->elementExists('css', ".page_action_copy")->click();
        $this->getSession()->wait(2 * 1000);
    }    
    
    /**
     * @Then I click to edit the page
     */
    public function iClickToEditThePage()
    {
        $this->assertSession()->elementExists('css', ".page_action_edit")->click();
        $this->getSession()->wait(2 * 1000);
    }     
    
    /**
     * @Then I click to show the structure of the page
     */
    public function iClickToShowTheStructureOfThePage()
    {
        $this->assertSession()->elementExists('css', ".veneer_blocks_widgets")->click();
        $this->getSession()->wait(2 * 1000);
    }     
    
    /**
     * @Then I switch to the edit form
     */
    public function iSwitchToTheEditForm()
    {
        $this->getSession()->switchToIframe("modalIframeId");
        $this->getSession()->wait(2 * 1000);
    }     
    
    /**
     * @Then I close the edit form
     */
    public function iCloseTheEditForm()
    {
        $this->getSession()->switchToIframe(null);
        //
        $xpath = "//body//button[contains(@class,'ui-dialog-titlebar-close')]//span";
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('xpath', $xpath)
        ); 
        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate XPath: "%s"', $xpath));
        }
        // ok, let's click on it
        $element->click();       
    }      
    
    /**
     * @When I save the edit form
     */
    public function iSaveTheEditForm()
    {
        $this->iSwitchToTheEditForm();
        //
        $button = "Save";
        $this->getSession()->getPage()->pressButton($button);
        //
        $this->getSession()->switchToIframe(null);
        //
        $this->getSession()->wait(4 * 1000);
    }    
    
    /**
     * @Then I click to the layout select field
     */
    public function iClickToTheLayoutSelectField()
    {
        $this->iSwitchToTheEditForm();
        //
        $xpath = "//*[contains(@id,'tabs')]//form[@class='myform']//div[@id='piapp_adminbundle_pagetype']//fieldset//div[4]//button";
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('xpath', $xpath)
        ); 
        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate XPath: "%s"', $xpath));
        }
        // ok, let's click on it
        $element->click();   
        //
        $this->getSession()->switchToIframe(null);
        //
        $this->getSession()->wait(2 * 1000);
    }  
    
    /**
     * @Then /^I select the new layout "([^"]*)"$/
     */
    public function iSelectTheNewLayout($layout)
    {
        $this->iSwitchToTheEditForm();
        //
        $xpath = "//body//label[contains(@for,'ui-multiselect-piapp_adminbundle_pagetype_layout-option-{$layout}')]//span";
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('xpath', $xpath)
        ); 
        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate XPath: "%s"', $xpath));
        }
        // ok, let's click on it
        $element->click();     
        //
        $this->getSession()->switchToIframe(null);
        //
        $this->getSession()->wait(2 * 1000);
    }  
    
    /**
     * @Then /^I click to edit the widget handler from the "([^"]*)" Zone$/
     */
    public function iClickToEditTheWidgetHandlerFromTheContentZone($content)
    {
        $this->getSession()->switchToIframe(null);
        //
        $xpath = "//body//sfynx[@data-name='{$content}']//span[@class='ui-dialog-title']";
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('xpath', $xpath)
        ); 
        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate XPath: "%s"', $xpath));
        }
        // ok, let's click on it
        $element->click();     
        //
        $this->getSession()->wait(2 * 1000);
        //
        $xpath = "//body//sfynx[@data-name='{$content}']//a[@class='block_action_import']";
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('xpath', $xpath)
        ); 
        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate XPath: "%s"', $xpath));
        }
        // ok, let's click on it
        $element->click();     
        //
        $this->getSession()->wait(6 * 1000);        
    }       
   
}
