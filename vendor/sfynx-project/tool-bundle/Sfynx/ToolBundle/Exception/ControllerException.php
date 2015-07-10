<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage Tool
 * @package    Exception
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2011-02-10
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Exception;

/**
 * Controller Exception
 *
 * @subpackage Tool
 * @package    Exception
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ControllerException extends \Exception
{
    /**
     * Returns the <Not Found Object> Exception.
     *
     * @param object $object
     * @return \Exception
     * @access public
     * @static
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public static function NotFoundObject($object) {
    	return new self(sprintf('Unable to find %s.', get_class($object)));
    }
        
    /**
     * Returns the <Not Found Entity> Exception.
     *
     * @param string $entityName
     * @return \Exception
     * @access public
     * @static
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public static function NotFoundEntity($entityName)
    {
        return new self(sprintf('Unable to find %s entity.', $entitName));
    }
    
    /**
     * Returns the <Not Found Option> Exception.
     *
     * @param string $option
     * @return \Exception
     * @access public
     * @static
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public static function NotFoundOption($option)
    {
        return new self(sprintf('Unable to find %s option.', $option));
    }    

    /**
     * Returns the <Call Ajax Only Supported> Exception.
     *
     * @param string $option
     * @return \Exception
     * @access public
     * @static
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public static function callAjaxOnlySupported($method)
    {
        return new self(sprintf('The method %s can be called only in ajax..', $method));
    }    

}