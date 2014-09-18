<?php
/**
 * This file is part of the <WS> project.
 *
 * @category   WS
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2013-03-26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\WsBundle\Builder;

use Sfynx\WsBundle\Builder\WsClientInterface;

/**
 * WS Factory Interface
 *
 * @category   WS
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface WsFactoryInterface {

    public function setClient(WsClientInterface $client);
    public function getClient();
}