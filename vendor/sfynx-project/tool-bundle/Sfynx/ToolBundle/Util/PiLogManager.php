<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   Tool
 * @package    Util
 * @author        Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2013-03-28
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Util;

use Sfynx\ToolBundle\Builder\PiLogManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of the Log manager.
 *
 * Here is an inline example:
 * <code>
 * $logger = $this->cointainer->get('sfynx.tool.log_manager');
 * $logger->setInit('log_test_myfunct');
 * $logger->setInfo("[LOG TEST] Begin launch"); 
 * $logger->setErr("[LOG TEST] Error info description");
 * $logger->setInfo("[END] End launch");
 * $logger->save(); 
 * </code>
 * 
 * @category   Tool
 * @package    Util
 * @author        Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiLogManager implements PiLogManagerInterface
{
   /**
    * @var \Symfony\Component\DependencyInjection\ContainerInterface
    */
   protected $container;
        
   /**
    * @var \Symfony\Bridge\Monolog\Logger
    */    
   protected $_logger;
   
   /**
    * @var array
    */
   protected $_info = null;   
   
   /**
    * @var string
    */
   protected $_path = "";   
   
   /**
    * @var string
    */
   protected $_name = "";   
   
   /**
    * @var string
    */
   protected $_file = "";   
   
   /**
    * Constructor.
    * 
    * @param    ContainerInterface $container The service container
    * @access    public
    * @author    Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
   public function __construct(ContainerInterface $container)
   {
        $this->container= $container;
        $this->_logger    = $this->container->get('logger');           
        if ($this->container->hasParameter("kernel.logs_dir")) {
               $this->setPath($this->container->getParameter("kernel.logs_dir"));
        }
   }   

   /**
    * Sets the log file path.
    *
    * @param    string    $path
    * @return    \Sfynx\ToolBundle\Util\PiLogManager
    * @access    public
    * @author    Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */   
   public function setPath($path)
   {
        $this->_path = realpath($path);      
        if (!empty($this->_path) && !empty($this->_name)) {
            $this->setFile($this->_path . '/' . $this->_name);
        }
      
        return $this;
   }
   
   /**
    * Sets the log file name.
    *
    * @param    string    $name
    * @return    \Sfynx\ToolBundle\Util\PiLogManager
    * @access    public
    * @author    Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
   public function setName($name)
   {
        $this->_name = $name;
        if (!empty($this->_path) && !empty($this->_name)) {
               $this->setFile($this->_path . '/' . $this->_name);
        }
                      
        return $this;
   }   
   
   /**
    * Sets the file.
    *
    * @param    string    $filePath
    * @param    octal        $mode    mode file
    * @return    \Sfynx\ToolBundle\Util\PiLogManager
    * @access    public
    * @author    Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
   public function setFile($filePath, $mode = 0777)
   {
        if (\Sfynx\ToolBundle\Util\PiFileManager::mkdirr(dirname($filePath), $mode)) {
            $this->_file = $filePath;
        }
           
        return $this;
   }   

   /**
    * Sets the log file by id.
    *
    * @param    string    $id
    * @param    string    $format
    * @param    int        $flag
    * @param    string    $path
    * @return    \Sfynx\ToolBundle\Util\PiLogManager
    * @access    public
    * @author    Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
   public function setInit($id, $format = "", $flag = FILE_APPEND, $path = "")
   {
        if (!empty($path)) {
               $this->setPath($path);
        }
        if (empty($format)) {
               $format = date('YmdHis');
        }
        // we create names of all files.
        $log_import     = $id . "." . $format.".log";    
        $date_import     = $id . ".last_import.txt";
        // we clear the container info
        $this->clearInfo();
        // we set the file name
        $this->setFile($this->_path .'/'. $log_import);
        // we set the content of all files.
        file_put_contents($this->_path .'/'. $date_import, date("d m Y H:i:s") ." -> ". $log_import.PHP_EOL, $flag); 
        
        return $this;
   }   

   /**
    * Add a info in the container.
    *
    * @param    string    $info
    * @param    boolean    $inLogger
    * @return    \Sfynx\ToolBundle\Util\PiLogManager
    * @access    public
    * @author    Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
   public function setInfo($info, $inLogger = true)
   {
        $this->_info[] = $info;
        if ($inLogger) {
            $this->_logger->info($info);
        }
        
        return $this;
   }
   
   /**
    * Clear the container info.
    *
    * @return    \Sfynx\ToolBundle\Util\PiLogManager
    * @access    public
    * @author    Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
   public function clearInfo()
   {
        $this->_info = null;
        
        return $this;
   }   
   
   /**
    * Add an error in the container.
    *
    * @param    string    $err
    * @param    boolean    $inLogger
    * @return    \Sfynx\ToolBundle\Util\PiLogManager
    * @access    public
    * @author    Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
   public function setErr($err, $inLogger = true)
   {
        $this->_info[] = $err;
        if ($inLogger) {
               $this->_logger->err($err);
        }
        
        return $this;
   }   
      
   /**
    * Delete the log file.
    *
    * @return    mixed    return 0 if the file is deleted correctly, otherwise return the instance.
    * @access    public
    * @author    Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
   public function delete()
   {
        $result = false;
        $dirpath = dirname($this->_file);
        if (@mkdir("$dirpath", 0777)) {}
        if (file_exists("$this->_file"))
        {
            unlink($path);
            $result = true;
        } else {
               $result = false;
        }
        if ($result) {
            return $this;
        } else {
            return false;
        }
   }
   
   /**
    * Save a content in the log file.
    *
    * @param    int    $flag
    * @param    octal    $mode    mode file
    * @return    mixed    return 0 if the file is save correctly, otherwise return the instance.
    * @access    public
    * @author    Etienne de Longeaux <etienne.delongeaux@gmail.com>
    */
   public function save($flag = FILE_APPEND, $mode = 0777)
   {
        if (\Sfynx\ToolBundle\Util\PiFileManager::mkdirr(dirname($this->_file), $mode)) {
            $result = file_put_contents($this->_file, PHP_EOL.implode("\n", $this->_info), $flag);
        } else {
            $result = false;
        }
        if ($result) {
            return $this;
        } else {
            return false;
        }
   }   
 
}