<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   Tool
 * @package    Util
 * @subpackage Route
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Builder;

/**
 * RouteTranslatorFactoryInterface interface.
 *
 * @category   Tool
 * @package    Util
 * @subpackage Route
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
interface RouteTranslatorFactoryInterface
{
    public function getRefererRoute($langue = '', $options = null);
    public function getLocaleRoute($langue = '', $options = null);
    public function getRoute($route_name = null, $params = null);
    public function getMatchParamOfRoute($param = null, $langue = '', $isGetReferer = false);
    public function getGenerate($name, array $locales, array $defaults = array(), array $requirements = array(), array $options = array());
}
