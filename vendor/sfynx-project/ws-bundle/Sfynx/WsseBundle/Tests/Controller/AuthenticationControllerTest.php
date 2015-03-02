<?php
/**
 * This file is part of the <Auth> project.
 * 
 * @category   Ws-se
 * @package    Tests
 * @subpackage Controller
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
namespace Sfynx\WsseBundle\Tests\Controller;

use Sfynx\AuthBundle\Tests\WebTestCase;
use Sfynx\AuthBundle\DataFixtures\ORM\UsersFixtures;
use Sfynx\WsseBundle\Security\Authentication\Provider\WsseProvider;

/**
 * Tests for the authentication controller of wsse
 *
 * @category   Ws-se
 * @package    Tests
 * @subpackage Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @group      functional
 * @group      database
 */
class AuthenticationControllerTest extends WebTestCase
{
    private $client;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::updateSchema();
    }

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIfRequestHaveNoXAuthTokenHaveA403StatusCode()
    {
        $this->client->request(
            'GET',
            '/api/wsse/v1/user/authentication',
            array(),
            array(),
            array(),
            ''
        );
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testIfRequestHaveWrongUsernameHaveA403StatusCode()
    {
        $this->client->request('GET',
            '/api/wsse/v1/user/authentication',
            array(),    // parameters
            array(),    // files
            array('HTTP_X-WSSE' => WsseProvider::makeToken("BadUserName", UsersFixtures::USER_PASSWORD)), // custom http header, prefix with HTTP_ ans uppercased
            '' //content
        );
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testIfRequestHaveWrongPasswordHaveA403StatusCode()
    {
        $this->client->request('GET',
            '/api/wsse/v1/user/authentication',
            array(),    // parameters
            array(),    // files
            array('HTTP_X-WSSE' => WsseProvider::makeToken(UsersFixtures::USER_USERNAME, "BadPassword")), // custom http header, prefix with HTTP_ ans uppercased
            '' //content
        );
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testIfRequestHaveValidUsernamePasswordHaveA200StatusCode()
    {
        $this->client->request('GET',
            '/api/wsse/v1/user/authentication',
            array(),    // parameters
            array(),    // files
            array('HTTP_X-WSSE' => WsseProvider::makeToken(UsersFixtures::USER_USERNAME, UsersFixtures::USER_PASSWORD)), // custom http header, prefix with HTTP_ ans uppercased
            '' //content
        );
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testIfRequestHaveValidUsernamePasswordHaveInformationAboutUser()
    {
        $this->client->request('GET',
            '/api/wsse/v1/user/authentication',
            array(),    // parameters
            array(),    // files
            array('HTTP_X-WSSE' => WsseProvider::makeToken(UsersFixtures::USER_USERNAME, UsersFixtures::USER_PASSWORD)), // custom http header, prefix with HTTP_ ans uppercased
            '' //content
        );
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        //
        $return = json_decode($this->client->getResponse()->getContent(), true);
        //
        $this->assertTrue(array_key_exists('userid', $return));
        $this->assertTrue(array_key_exists('username', $return));
        $this->assertTrue(array_key_exists('firstname', $return));
        $this->assertTrue(array_key_exists('lastname', $return));
        $this->assertTrue(array_key_exists('email', $return));
        $this->assertTrue(array_key_exists('address', $return));
        $this->assertTrue(array_key_exists('cp', $return));
        $this->assertTrue(array_key_exists('city', $return));
        $this->assertTrue(array_key_exists('country', $return));
    }
}
