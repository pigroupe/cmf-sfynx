<?php
/**
 * This file is part of the <Ws-se> project.
 *
 * @category   Ws-se
 * @package    Model
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\WsseBundle\Model;

use Sfynx\AuthBundle\Entity\User;

/**
 * @category   Ws-se
 * @package    Model
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class UserWS
{
    /** @var User */
    private $user;

    /** @var int */
    private $expired;

    /** @var string */
    private $error = '';

    public function __call($name, $arguments)
    {
        if ($this->user) {
            return call_user_func_array(array($this->user, $name), $arguments);
        }

        return false;
    }

    public function __construct(User $user = null, $expired = 1800)
    {
        $this->user = $user;
        $this->expired = $expired;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $userInArray =  array(
            'isconnected' => $this->user->isConnected($this->expired),
            'lastname'      => $this->user->getName() ? $this->getName() : $this->getUserName(),
            'firstname' => $this->user->getNickname(),
            'email'     => $this->user->getEmail(),
            'adress'      => $this->user->getAddress(),
            'cp'        => $this->user->getZipCode(),
            'city'     => $this->user->getCity(),
            'error'     => $this->getError(),
        );

        return $userInArray;
    }

    /**
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param int $expired
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;
    }

    /**
     * @return int
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @param \NosBelIdees\UserBundle\Propel\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \NosBelIdees\UserBundle\Propel\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
