<?php
/**
 * This file is part of the <Auth> project.
 * 
 * @category   Auth
 * @package    Tests
 * @subpackage Security
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
namespace Sfynx\AuthBundle\Tests\Security;

use Sfynx\AuthBundle\Tests\WebTestCase;
use Sfynx\AuthBundle\DataFixtures\ORM\UsersFixtures;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Tests for the back office controller of NosBelIdeesSiteBundle
 *
 * @category   Auth
 * @package    Tests
 * @subpackage Security
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @group      functional
 * @group      database
 */
class LoginTest extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();        
        static::updateSchema();
        static::emptyLoginFailure();
    }

    public function testLoginLink()
    {
        $client = static::createClient();
        
        $client->request('GET', '/en/');
        $this->assertStatusCode('200', $client);
        $client->request('GET', self::URL_CONNECTION);

        return $client;
    }

    /**
     * @depends testLoginLink
     */
    public function testLoginError(Client $client)
    {
        $msg = static::$translator->trans('Bad credentials');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(self::URL_CONNECTION, $client->getRequest()->getRequestUri());
        
        $this->loginRoleAdmin($client, false, UsersFixtures::ADMIN_USERNAME, 'error');
        $this->assertStatusCode('302', $client);
        $this->assertEquals(self::URL_CONNECTION_CHECK, $client->getRequest()->getRequestUri());

        $crawler = $client->followRedirect();
        $this->assertEquals(self::URL_CONNECTION_FAILURE, $client->getRequest()->getRequestUri());
        
        $crawler = $client->followRedirect();
        $this->assertCount(
            1,
            $crawler->filter('html:contains("'.$msg.'")')
        );
        
        $this->loginRoleAdmin($client, false, 'error_username', UsersFixtures::ADMIN_PASS);
        $this->assertStatusCode('302', $client);
        $this->assertEquals(self::URL_CONNECTION_CHECK, $client->getRequest()->getRequestUri());
        
        $crawler = $client->followRedirect();
        $this->assertEquals(self::URL_CONNECTION_FAILURE, $client->getRequest()->getRequestUri());
        
        $crawler = $client->followRedirect();
        $this->assertCount(
            1,
            $crawler->filter('html:contains("'.$msg.'")')
        );

        return $client;
    }

    /**
     * @depends testLoginError
     */
    public function testLogin(Client $client)
    {
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(self::URL_CONNECTION, $client->getRequest()->getRequestUri());

        $this->loginRoleAdmin($client, false);
        if ($profile = $client->getProfile()) {
            $this->assertHasEventsCalled($profile, 'EventListener\HandlerLogin');
            $this->assertHasEventsCalled($profile, 'EventListener\HandlerLocale');
            $this->assertHasEventsCalled($profile, 'EventListener\HandlerRequest');
        }

        $crawler = $client->followRedirect();
        $this->assertStatusCode('200', $client);
        $this->assertEquals('/admin/home', $client->getRequest()->getRequestUri());

        return $client;
    }

    /**
     * @depends testLogin
     */
    public function testLogout(Client $client)
    {
        $this->setSecurityContextUser();
        
        $crawler = $client->getCrawler();
        $crawler = $client->request('GET', self::URL_DECONNECTION);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals(self::URL_DECONNECTION, $client->getRequest()->getRequestUri());
        
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('/en/', $client->getRequest()->getRequestUri());
    }
}
