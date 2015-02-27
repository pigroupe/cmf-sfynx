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
namespace Sfynx\WsseBundle\Tests\Security;

use Sfynx\AuthBundle\Tests\WebTestCase;
use Sfynx\WsseBundle\Security\Authentication\Provider\WsseProvider;

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
        
        static::updateSchema();
    }

    public function testRequestWithoutXAuthToken()
    {
        static::emptyCache();
        $client = static::createClient();

        $client->request('GET', '/api/wsse/v1/user/authentication');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testRequestWithXAuthTokenInvalid()
    {
        static::emptyCache();
        $client = static::createClient();

        $client->request('GET',
            '/api/wsse/v1/user/authentication',
            array(),    // parameters
            array(),    // files
            array('HTTP_X-WSSE' => WsseProvider::makeToken("BadName", static::USER_PASSWORD)), // custom http header, prefix with HTTP_ ans uppercased
            '' //content
        );
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testRequestWithValidXAuthToken()
    {
        static::emptyCache();
        $client = static::createClient();
        $client->request('GET', static::URL_DECONNECTION);

        $client->request('GET',
            '/api/wsse/v1/user/authentication',
            array(),    // parameters
            array(),    // files
            array('HTTP_X-WSSE' => WsseProvider::makeToken(static::USER_USERNAME, static::USER_PASSWORD)), // custom http header, prefix with HTTP_ ans uppercased
            '' //content
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
