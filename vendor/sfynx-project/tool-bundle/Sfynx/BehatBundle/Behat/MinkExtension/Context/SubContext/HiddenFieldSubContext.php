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
class HiddenFieldSubContext extends RawMinkContext
{
    /**
     * @var string $browserName
     */    
    protected $browserName;
    
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters
     */
    public function __construct()
    {
        $this->browserName = $this->getMinkParameter('browser_name');
    }

    /**
     * Checks that form hidden field with specified id|name has specified value.
     *
     * @Then /^the "(?P<field>(?:[^"]|\\")*)" hidden field should contain "(?P<value>(?:[^"]|\\")*)"$/
     */
    public function hiddenFieldValueEquals($field, $value)
    {
        $node = $this->findHiddenField($field);
        $actual = $node->getValue();
        $regex  = '/^' . preg_quote($value, '/') . '/ui';
        if (!preg_match($regex, $actual)) {
            $message = sprintf('The hidden field "%s" value is "%s", but "%s" expected.', $field, $actual, $value);
            throw new ExpectationException($message, $this->getSession());
        }
    }
    
    /**
     * Checks that form hidden field with specified id|name has specified value.
     *
     * @Then /^the "(?P<field>(?:[^"]|\\")*)" hidden field should not contain "(?P<value>(?:[^"]|\\")*)"$/
     */
    public function hiddenFieldValueNotEquals($field, $value)
    {
        $node = $this->findHiddenField($field);
        $actual = $node->getValue();
        $regex  = '/^' . preg_quote($value, '/') . '/ui';
        if (preg_match($regex, $actual)) {
            $message = sprintf('The hidden field "%s" value is "%s", but it should not be.', $field, $actual);
            throw new ExpectationException($message, $this->getSession());
        }
    }
    
    /**
     * @param string $field
     *
     * @return \Behat\Mink\Element\NodeElement|null
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    private function findHiddenField($field)
    {
        $node = $this->getSession()->getPage()->find(
            'xpath',
            strtr(
                ".//input[./@type = 'hidden'][(./@id = '%locator%' or ./@name = '%locator%')]",
                array('%locator%' => $field)
            )
        );
        if (null === $node) {
            throw new ElementNotFoundException($this->getSession(), 'hidden field', 'id|name', $field);
        }
        return $node;
    }
    
//    /**
//     * Take screenshot when step fails.
//     * Works only with Selenium2Driver.
//     *
//     * @AfterStep
//     */
//    public function takeScreenshotAfterFailedStep($event)
//    {
//        if (4 === $event->getResult()) {
//            $driver = $this->getSession()->getDriver();
//            if (!($driver instanceof Selenium2Driver)) {
//                throw new UnsupportedDriverActionException('Taking screenshots is not supported by %s, use Selenium2Driver instead.', $driver);
//            }
//            $directory = 'build/behat/' . $event->getLogicalParent()->getFeature()->getTitle();
//            if (!is_dir($directory)) {
//                mkdir($directory, 0777, true);
//            }
//            $filename = sprintf('%s_%s_%s_%s.%s', $event->getLogicalParent()->getTitle(), $this->browserName, date('YmdHis'), uniqid('', true), 'png');
//            file_put_contents($directory . '/' . $filename, $driver->getScreenshot());
//        }
//    }  
}
