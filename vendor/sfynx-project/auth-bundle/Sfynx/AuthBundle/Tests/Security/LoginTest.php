<?php
/**
 * This file is part of the <Auth> project.
 * 
 * @category   Auth
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
namespace Sfynx\AuthBundle\Tests\Security;

use Sfynx\AuthBundle\Tests\WebTestCase;
use Sfynx\AuthBundle\DataFixtures\ORM\UsersFixtures;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Tests for the back office controller of NosBelIdeesSiteBundle
 *
 * @category   Auth
 * @package    Tests
 * @subpackage Controller
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
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(self::URL_CONNECTION, $client->getRequest()->getRequestUri());
        $this->loginRoleAdmin($client, UsersFixtures::ADMIN_USERNAME, 'error');
        $this->assertStatusCode('403', $client);
        
        $crawler = $client->followRedirect();
        $this->assertEquals(self::URL_CONNECTION, $client->getRequest()->getRequestUri());
        
        $msg = static::$translator->trans('Bad credentials');
        $this->assertCount(
            1,
            $crawler->filter('html:contains("'.$msg.'")')
        );
        $this->loginRoleAdmin($client, 'error_username', UsersFixtures::ADMIN_PASS);
        $this->assertStatusCode('403', $client);
        
        $crawler = $client->followRedirect();
        $this->assertEquals(self::URL_CONNECTION, $client->getRequest()->getRequestUri());
        $this->assertCount(
            1,
            $crawler->filter('html:contains("'.$msg.'")')
        );

        return $client;
    }

//    /**
//     * @depends testLoginError
//     */
//    public function testLogin(Client $client)
//    {
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertEquals(self::URL_CONNECTION, $client->getRequest()->getRequestUri());
//
//        $this->loginRoleAdmin($client);
//        if ($profile = $client->getProfile()) {
//            $this->assertHasPropelHandlerCalled($profile, 'handleUpdateUser');
//            $this->assertHasPropelHandlerCalled($profile, 'UserActionsSubscriber::handleUpdateUser');
//        }
//        $this->assertEquals(302, $client->getResponse()->getStatusCode());
//        $this->assertEquals('/verification', $client->getRequest()->getRequestUri());
//
//        $crawler = $client->followRedirect();
//        $this->assertEquals('/', $client->getRequest()->getRequestUri());
//        $this->assertCount(0, $crawler->filter('header a:contains("Je m\'inscris")'));
//        $this->assertCount(0, $crawler->filter('header a:contains("Je m\'identifie")'));
//
//        return $client;
//    }
//
//    /**
//     * @depends testLogin
//     */
//    public function testLogout(Client $client)
//    {
//        $crawler = $client->getCrawler();
//        $crawler = $client->request('GET', self::URL_DECONNECTION);
//        $this->assertEquals(302, $client->getResponse()->getStatusCode());
//        $this->assertEquals(self::URL_DECONNECTION, $client->getRequest()->getRequestUri());
//        
//        $crawler = $client->followRedirect();
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertEquals('/', $client->getRequest()->getRequestUri());
//    }
//
//    public function testLoginDontChangeLastUpdate()
//    {
//        /** @var $refUser User */
//        $refUser = UserQuery::create()->filterByEmail(self::USER_EMAIL)->findOne();
//        $InitialLastUpdate = $refUser->getUpdatedAt()->format('Y-m-d H:i:s');
//        sleep(1);
//
//        $this->loginRoleUser();
//
//        $refUser = UserQuery::create()->filterByEmail(self::USER_EMAIL)->findOne();
//        $FinalLastUpdate = $refUser->getUpdatedAt()->format('Y-m-d H:i:s');
//
//        $this->assertEquals($InitialLastUpdate, $FinalLastUpdate);
//    }
}
