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
 * Regex builder interface.
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
interface PiRegexManagerBuilderInterface
{
    public static function stripTrailingSlash($string);
    public static function replaceTag($tag, $replacement, $content, $attributes = null);
    public static function simplifyDatetime($string);
    public static function isDateTime($_string);
    public static function isMd5($_string);
    public static function findinside($start, $end, $string);
    public static function verifByRegularExpression($chaine, $typeExpression = "no");
    public static function searchIdByTag($chaine,$balise);
    public static function searchLinkByParam($chaine,$balise);
    public static function deleteDisplayNoneTag($w_var, $tag, $replaceTerm = '');
    public static function hex2rgb($color);
}
