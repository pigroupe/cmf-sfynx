<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage   Tool
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2013-03-28
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Builder;

/**
 * Timer Manager Interface.
 *
 * @subpackage Tool
 * @package    Builder
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
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
