<?php
/**
 * This file is part of the <Auth> project.
 *
 * @subpackage Auth
 * @package    Tests
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-03-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\Tests\Controller;

use Sfynx\AuthBundle\Tests\WebTestCase;

/**
 * Tests for the back office controller of NosBelIdeesSiteBundle
 *
 * @package    Auth
 * @subpackage Tests
 * @author     Simon Constans <simon.constans@rappfrance.com>
 *
 * @group functional
 * @group database
 */
class BackOfficeControllerTest extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::loadFixtures();
    }

    /**
     * Content Test of temporary homepage
     */
    public function testRedirectsIfUserNotLogged()
    {
        $client = static::createClient();

        $client->request('GET', '/admin/home');
        $this->assertStatusCode('302', $client);
    }

    public function testNormalUserReceivesA403StatusCode()
    {
        $client = static::createClient();

        $this->loginRoleUser($client)
            ->request('GET', '/admin/home');
        $this->assertStatusCode('403', $client);
    }

    public function testAdminConnectsSucessfully()
    {
        $client = static::createClient();
        $this->loginRoleAdmin($client)
            ->request('GET', '/admin/home');

        $this->assertStatusCode('200', $client);
    }
}
