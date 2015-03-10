<?php
namespace Sfynx\BehatBundle\Features\Context\bootstrap;

use Behat\MinkExtension\Context\MinkContext as BaseMinkContext;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;

class AjaxSubContext extends BaseMinkContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters
     */
    public function __construct()
    {
        print_r('cocinc');exit;
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
