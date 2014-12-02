<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage Tool
 * @package    Util
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2013-03-28
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Util;

use Sfynx\ToolBundle\Builder\PiTimerManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\ToolBundle\Exception\ServiceException;

/**
 * Description of the Timer manager.
 *
 * Here is an inline example:
 * <code>
 * $timer = $this->container->get('sfynx.tool.timer_manager');
 * $timer->flush();
 * $timer->start('timer_fct_1');
 * $timer->end('timer_fct_1', true); 
 * $timer->start('timer_fct_2'); * 
 * $timer->end('timer_fct_2', false); 
 * print_r($timer->reporting());
 * 
 * $timer = $this->container->get('sfynx.tool.timer_manager')->flush();
 * $timer->start('timer_fct_1');
 * $timer->start('timer_fct_2', 'timer_fct_1');
 * print_r($timer->reporting());
 * </code>
 * 
 * @subpackage Tool
 * @package    Util
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiTimerManager implements PiTimerManagerInterface
{
   /**
    * @var \Symfony\Component\DependencyInjection\ContainerInterface
    */
   protected $container;
   
   /**
    * @var array
    */
   protected $timer = null;   
   
   /**
    * Constructor.
    * 
    * @param ContainerInterface $container The service container
    * 
    * @return void
    * @access public
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
   public function __construct(ContainerInterface $container)
   {
        $this->container = $container;
   }   

   /**
    * Flush the timer.
    *
    * @param string $etag An Etag value 
    * 
    * @return PiTimerManager
    * @access public
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
    public function flush($etag = '')
    {
        if (empty($etag)) {
            $this->timer = null;
        } elseif (isset($this->timer['$etag'])) {
            unset($this->timer['$etag']);
        }
        
        return $this;
    }  
   
   /**
    * Start a timer in the container.
    *
    * @param string $etag An Etag value 
    * 
    * @return void
    * @access public
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
    public function start($etag, $endEtag = '')
    {
        if (!isset($this->timer[$etag])) {
           $this->timer[$etag]['start'] = microtime();
        }
        if (!empty($endEtag)
                && isset($this->timer[$endEtag])
        ) {
            $this->end($endEtag, false);
        }
    }  
    
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
    public function end($etag, $print = false)
    {
        if (isset($this->timer[$etag]['start'])) {
            $this->timer[$etag]['timer'] = microtime() - $this->timer[$etag]['start'];
            if ($print) {
               print_r($this->timer[$etag]['timer']);
            }
            
            return $this->timer[$etag]['timer'];            
        }
    }  
    
   /**
    * Reporting
    *
    * @param string $etag An Etag value 
    * 
    * @return string
    * @access public
    * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
    public function reporting($etag = '')
    {
        if (!empty($etag) 
                && isset($this->timer[$etag]['timer'])
        ) {
            return "TIMER::$etag::" .$this->timer[$etag]['timer']."\n";
        } else {
            $result = "";
            foreach($this->timer as $etag => $timer) {
                if (!isset($timer['timer'])) {
                    $this->timer[$etag]['timer'] = microtime() - $this->timer[$etag]['start'];
                    $timer['timer'] = $this->timer[$etag]['timer'];
                }
                $result .= "TIMER::$etag::" . $timer['timer']."\n";
            }
            
            return $result;
        }
    }      
}
