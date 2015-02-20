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
namespace Sfynx\WsseBundle\Tests;

use Sfynx\AuthBundle\Tests\WebTestCase;

/**
 * Tests for the simple partner firewall of NosBelIdeesWebserviceBundle
 *
 * @category   Ws-se
 * @package    Tests
 * @subpackage Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @group      functional
 * @group      database
 */
class WsseAuthentificationTest extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::loadFixtures();
    }

    public function testRequestWithoutXAuthToken()
    {
        $client = static::createClient();

        $client->request('GET', '/api/wsse/v1/user/authentication');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testRequestWithXAuthTokenInvalid()
    {
        $md5 = md5('invalidTokenForTest' . date('Y-m-d'));
        $client = static::createClient();

        $client->request('GET',
            '/api/wsse/v1/user/authentication',
            array(),    // parameters
            array(),    // files
            array('x-wsse' => $md5), // custom http header, prefix with HTTP_ ans uppercased
            '' //content
        );
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testRequestWithValidXAuthToken()
    {
        $md5 = md5('tokenForTest' . date('Y-m-d'));
        $client = static::createClient();

        $client->request('GET',
            '/api/wsse/v1/user/authentication',
            array(),    // parameters
            array(),    // files
            array('x-wsse' => $md5), // custom http header, prefix with HTTP_ ans uppercased
            '' //content
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
