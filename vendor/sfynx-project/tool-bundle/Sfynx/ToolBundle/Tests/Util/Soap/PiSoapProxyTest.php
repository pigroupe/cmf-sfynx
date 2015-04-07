<?php

namespace Sfynx\ToolBundle\Tests\Util\Soap;

use Sfynx\ToolBundle\Util\Soap\PiSoapProxy;

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
