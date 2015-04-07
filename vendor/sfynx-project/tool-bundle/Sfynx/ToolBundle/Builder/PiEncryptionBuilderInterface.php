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
 * Encrypte builder interface.
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
interface PiEncryptionBuilderInterface
{
    public static function getSupportedEncryptionTypes();
    public static function encryptPassword($_password, $_method);
    public static function getRandomString($_length);

    public static function encryptFilter($string, $key = "0A1TG4GO");
    public static function decryptFilter($string, $key = "0A1TG4GO");

    public static function obfuscateLinkEncrypt($url, $_base16 = "0A12B34C56D78E9F");
    public static function obfuscateLinkDecrypt($balise = "a", $class = "hiddenLink", $base16 = "0A12B34C56D78E9F");
}
