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
}
