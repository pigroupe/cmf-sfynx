<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   Tool
 * @package    Tests
 * @subpackage Proxy
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Tests\Util\Soap;

use Sfynx\ToolBundle\Util\Soap\PiSoapProxy;

/**
 * @category   Tool
 * @package    Tests
 * @subpackage Proxy
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiSoapProxyTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->soap_client = $this->getMockBuilder('Sfynx\ToolBundle\Util\Soap\PiSoapManagerInterface')
                ->setConstructorArgs(array($this->container))
                ->getMock(); 
        $this->soap_model = $this->getMock('Sfynx\ToolBundle\Util\Soap\PiSoapModelInterface');
    }
    
    public function testProxy()
    {
        $proxy = $this->getMockBuilder('Sfynx\ToolBundle\Util\Soap\PiSoapProxy')
                ->setConstructorArgs(array($this->soap_client))
                ->getMock();
    }
    
    public function testSend()
    {
        $proxy = new PiSoapProxy($this->soap_client);
        $proxy->send($this->soap_model);
    }    
}
