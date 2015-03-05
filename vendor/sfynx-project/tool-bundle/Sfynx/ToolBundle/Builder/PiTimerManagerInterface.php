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
 * Timer builder Interface.
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
interface PiTimerManagerInterface
{
  /**
    * Flush the timer.
    *
    * @param string $etag An Etag value 
    * 
    * @return PiTimerManager
    * @access public
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
    public function flush($etag = '');
   
   /**
    * Start a timer in the container.
    *
    * @param string $etag An Etag value 
    * 
    * @return void
    * @access public
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
    public function start($etag, $endEtag = '');
    
   /**
    * End a timer in the container.
    *
    * @param string  $etag  An Etag value 
    * @param boolean $print True to print the timer of the etag
    * 
    * @return string
    * @access public
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
    public function end($etag, $print = false);
    
   /**
    * Reporting
    *
    * @param string $etag An Etag value 
    * 
    * @return string
    * @access public
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
    public function reporting($etag = '');
}
