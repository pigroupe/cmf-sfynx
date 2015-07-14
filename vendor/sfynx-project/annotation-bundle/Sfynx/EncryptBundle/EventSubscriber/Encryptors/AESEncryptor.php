<?php
/**
 * This file is part of the <Encrypt> project.
 * 
 * @uses       PiEncryptorInterface
 * @subpackage Encrypt
 * @package    EventSubscriber 
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2014-06-02
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\EncryptBundle\EventSubscriber\Encryptors;

use Sfynx\EncryptBundle\Builder\PiEncryptorInterface;

/**
 * Class for AES encryption
 * 
 * @uses       PiEncryptorInterface
 * @subpackage Encrypt
 * @package    EventSubscriber 
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class AESEncryptor implements PiEncryptorInterface 
{
    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $initializationVector;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options) {
        $key = isset($options['secret_key']) ? (string) $options['secret_key'] : '';
        $this->secretKey = md5($key);
        $this->initializationVector = mcrypt_create_iv(
            mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB),
            MCRYPT_RAND
        );
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($data) {
        return trim(base64_encode(mcrypt_encrypt(
            MCRYPT_RIJNDAEL_256,
            $this->secretKey,
            $data,
            MCRYPT_MODE_ECB,
            $this->initializationVector
        )));
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($data) {
        return trim(mcrypt_decrypt(
            MCRYPT_RIJNDAEL_256,
            $this->secretKey,
            base64_decode($data),
            MCRYPT_MODE_ECB,
            $this->initializationVector
        ));
    }
}
