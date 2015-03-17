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
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;

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
class AjaxSubContext extends RawMinkContext
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
    * @Then /^I wait for the "(?P<classname>(?:[^"]|\\")*)" box to appear$/
    */
    public function iWaitForTheBoxToAppear($classname)
    {
       $this->getSession()->wait(5000, "$('.$classname').children().length > 0");
    }
    
    /**
     * @BeforeStep @javascript
     */
    public function beforeStep($event)
    {
        $text = $event->getStep()->getText();
        if (preg_match('/(follow|press|click|submit)/i', $text)) {
            $this->ajaxClickHandler_before();
        }
    }
 
    /**
     * @AfterStep @javascript
     */
    public function afterStep($event)
    {
        $text = $event->getStep()->getText();
        if (preg_match('/(follow|press|click|submit)/i', $text)) {
            $this->ajaxClickHandler_after();
        }
    }
 
    /**
     * Hook into jQuery ajaxStart and ajaxComplete events.
     * Prepare __ajaxStatus() functions and attach them to these events.
     * Event handlers are removed after one run.
     */
    public function ajaxClickHandler_before()
    {
        $javascript = <<<JS
window.jQuery(document).one('ajaxStart.ss.test', function(){
    window.__ajaxStatus = function() {
        return 'waiting';
    };
});
window.jQuery(document).one('ajaxComplete.ss.test', function(){
    window.__ajaxStatus = function() {
        return 'no ajax';
    };
});
JS;
        $this->getSession()->executeScript($javascript);
    }
 
    /**
     * Wait for the __ajaxStatus()to return anything but 'waiting'.
     * Don't wait longer than 5 seconds.
     */
    public function ajaxClickHandler_after()
    {
        $this->getSession()->wait(5000,
            "(typeof window.__ajaxStatus !== 'undefined' ?
                window.__ajaxStatus() : 'no ajax') !== 'waiting'"
        );
    }    
}
