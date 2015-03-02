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
namespace Sfynx\AuthBundle\Tests\Controller;

use Sfynx\AuthBundle\Tests\WebTestCase;

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
class SecurityControllerTest extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();        
        static::updateSchema();
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
