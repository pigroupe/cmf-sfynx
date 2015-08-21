<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @category   Cmf
 * @package    Feature
 * @subpackage Extends
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

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Sfynx\BehatBundle\Behat\MinkExtension\Context\FeatureContext as baseFeatureContext;

/**
 * Defines application features from the specific context.
 * 
 * class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
 * 
 * @category   Cmf
 * @package    Feature
 * @subpackage Extends
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-03-02 
 */
class FeatureContext extends baseFeatureContext
{
    /** @var \Behat\MinkExtension\Context\MinkContext */
    private $minkCmfContext;
            
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }
    
    /** @BeforeScenario */
    public function CmfgatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        $this->minkCmfContext  = $environment->getContext('Sfynx\CmfBundle\Features\Context\MinkContext');
    }     
}
