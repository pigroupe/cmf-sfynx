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
 * Log builder Interface.
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
interface PiLogManagerInterface
{
    public function setPath($path);
    public function setName($name);
    public function setFile($filePath, $mode = 0777);
    public function setInit($id, $format = "YmdHis", $flag = FILE_APPEND, $path = "");
    public function setErr($err);
    public function setInfo($info);
    public function setLog($level, $message, array $context = array());
    public function clearInfo();
    public function delete();
    public function save($flag = FILE_APPEND, $mode = 0777);
}
