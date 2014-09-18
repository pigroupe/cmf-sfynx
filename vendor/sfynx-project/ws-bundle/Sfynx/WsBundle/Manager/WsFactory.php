<?php
/**
 * This file is part of the <web service> project.
 *
 * @uses WsFactoryInterface
 * @category WS
 * @package Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2013-03-26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\WsBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\WsBundle\Builder\WsFactoryInterface;
use Sfynx\WsBundle\Builder\WsClientInterface;

/**
 * Client factory.
 * 
 * @uses WsFactoryInterface
 * @category WS
 * @package Manager
 * @author   Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class WsFactory implements WsFactoryInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $_container;

    /**
     * @var \Sfynx\WsBundle\Builder\WsClientInterface
     */
    private $_client;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
    }

    /**
     * Gets the container instance.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function getContainer()
    {
        return $this->_container;
    }

    /**
     * Inject a client.
     * 
     * @param \Sfynx\WsBundle\Builder\WsClientInterface $client
     * @return void
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function setClient(WsClientInterface $client)
    {
        if (is_object($client) && ( $client instanceof WsClientInterface ))
            $this->_client = $client;
        else
            throw new \Exception('Invalid Ws Client Interface');
    }

    /**
     * get the client.
     *
     * @return \Sfynx\WsBundle\Builder\WsClientInterface
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getClient()
    {
        return $this->_client;
    }

}