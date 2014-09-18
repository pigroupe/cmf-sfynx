<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   Tool
 * @package    Exception
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-02-10
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Exception;

/**
 * Extension Exception
 *
 * @category   Tool
 * @package    Exception
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ExtensionException extends \Exception
{
    /**
     * Returns the <File UnDefined> Exception.
     *
     * @param string $file
     * @return \Exception
     * @access public
     * @static
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public static function FileUnDefined($file)
    {
        return new self(sprintf('File %s doesn\'t exist in the web/bundle !', $file));
    }
    
     /**
     * Returns the <Option Value Not Specified> Exception.
     *
     * @param string $optionName
     * @param string $className
     * @return \Exception
     * @access public
     * @static
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public static function optionValueNotSpecified($optionName, $className = '')
    {
    	if (!empty($className)) {
    		return new self(sprintf('Option %s not specified in parameters in the class %s ', $optionName, $className));
    	} else {
    		return new self(sprintf('Option %s not specified ! ', $optionName));
    	}
    }    
    
}