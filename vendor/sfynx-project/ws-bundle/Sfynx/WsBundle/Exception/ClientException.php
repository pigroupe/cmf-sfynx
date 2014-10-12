<?php
/**
 * This file is part of the <web service> project.
 *
 * @subpackage   WS
 * @package    Exception
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2013-03-26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\WsBundle\Exception;

/**
 * Exception
 *
 * @subpackage   WS
 * @package    Exception
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ClientException extends \Exception
{
    /**
     * Returns the <client Not Supported> Exception.
     *
     * @param string $clienteName
     * @param string $className
     * @return \Exception
     * @access public
     * @static
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public static function clientNotSupported($clienteName, $className) {
    	return new self(sprintf('The %s client %s is not yet supported in the bundle %s.', $clienteName, $className));
    }    

    /**
     * Returns the <call Method Not Supported> Exception.
     *
     * @param string $method
     * @return \Exception
     * @access public
     * @static
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public static function callBadAuthRequest($class) {
    	return new self(sprintf('Authentication request doesn\'t call correctly in %s class.', $class));
    }   
}