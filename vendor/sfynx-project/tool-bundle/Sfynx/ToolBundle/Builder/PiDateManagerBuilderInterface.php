<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   Tool
 * @package    Util
 * @subpackage Builder
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
 * Date builder interface.
 *
 * @category   Tool
 * @package    Util
 * @subpackage Builder
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
interface PiDateManagerBuilderInterface
{
    public function parse($date, $locale = null);
    public function format($date, $dateType = 'medium', $timeType = 'none', $locale = null, $pattern = null);
    public function parseTimestamp($date, $locale = null);
    public function createdAgoFilter(\DateTime $dateTime);
    public function RelativeTime(\DateTime $dateTime, $from = null);
    public function NextDate(\DateTime $dateTime, $from = null);
    public function allMonths($locale);
    public function allDays($locale);
    public function nextOrLastList($year, $month, $day, $order, $number, $type = 'month', $format = 'Y-m-d');
}
