<?php
/**
 * This file is part of the <Core> project.
 *
 * @category   Core
 * @package    Exception
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-02-10
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Exception;

/**
 * Widget Exception
 *
 * @category   Core
 * @package    Exception
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class EntityException extends \Exception
{
    /**
     * Returns the <Id Entity UnDefined> Exception.
     *
     * @param integer $id
     * @param string $className
     * @return \Exception
     * @access public
     * @static
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public static function IdEntityUnDefined($id, $className)
    {
        return new self(sprintf('Id %s is not defined like a %s entity !', $id, $className));
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
}