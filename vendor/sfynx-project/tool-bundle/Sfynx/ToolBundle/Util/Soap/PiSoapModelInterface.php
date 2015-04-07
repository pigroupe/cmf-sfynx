<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage Tool
 * @package    Soap
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Util\Soap;

/**
 * Soap Model Interface
 *
 * @subpackage Tool
 * @package    Soap
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface PiSoapModelInterface
{
    /**
     * Set soap client
     *
     * @param PiSoapManagerInterface $soapmanager
     * 
     * @return void
     */    
    public function setSoapManager(PiSoapManagerInterface $soapmanager);
    
    /**
     * Set model option
     *
     * @param null|array|object $option
     * 
     * @return void
     */       
    public function setOption($option);
    
    /**
     * Set create model
     *
     * @return void
     */      
    public function create();
    
    /**
     * Set call model
     *
     * @return object The result of the soap call
     */       
    public function call();
}
