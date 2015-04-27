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
 * Tests for the create controller of wsse
 *
 * @category   Ws-se
 * @package    Tests
 * @subpackage Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @group      functional
 * @group      database
 */
class CreateControllerTest extends WebTestCase
{
    private $client;
    
    /**
     * Form data fixtures. In longer term this could be moved to a YAML file?
     */
    private static $UserFixtures = array(
        'first_name'=> 'John',
        'last_name' => 'Do',
        'email'     => "johntest@myemail.com",
        'connexion' => array(
            'username' => "JohnUserName",
            'password' => "JohnPsw123",
            'role'     => 'ROLE_USER'
        ),
        'location'  => array(
            'address' => '12, avenue du Capitaine Glarner',
            'cp'      => '93400',
            'city'    => ' Saint-Ouen',
            'country' => 'France'
        )
    );      

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::updateSchema();
    }

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIfRequestHaveNoDataResponseHaveA400StatusCode()
    {
        $this->client->request('POST',
            '/api/wsse/v1/user/create',
            array(),    // parameters
            array(),    // files
            array('HTTP_X-WSSE' => WsseProvider::makeToken(UsersFixtures::ADMIN_USERNAME, UsersFixtures::ADMIN_PASSWORD)), // custom http header, prefix with HTTP_ ans uppercased
            '' //content
        );
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $return = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals("You must send datas", $return['error']);
    }

    public function testIfRequestHaveNoJsonResponseHaveA400StatusCode()
    {
        $this->client->request('POST',
            '/api/wsse/v1/user/create',
            array(),    // parameters
            array(),    // files
            array('HTTP_X-WSSE' => WsseProvider::makeToken(UsersFixtures::ADMIN_USERNAME, UsersFixtures::ADMIN_PASSWORD)), // custom http header, prefix with HTTP_ ans uppercased
            'I am not a json content'
        );
        $this->assertEquals(415, $this->client->getResponse()->getStatusCode());
        $return = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals("Sended datas must be formated in Json", $return['error']);
    }

    public function testIfRequestHaveIncompletesDatasResponseHaveA400StatusCode()
    {
        $this->client->request('POST',
            '/api/wsse/v1/user/create',
            array(),    // parameters
            array(),    // files
            array('HTTP_X-WSSE' => WsseProvider::makeToken(UsersFixtures::ADMIN_USERNAME, UsersFixtures::ADMIN_PASSWORD)), // custom http header, prefix with HTTP_ ans uppercased
            static::getUserDataInJson(array(), 'first_name')
        );
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $return = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue(isset($return['first_name']), "missing data is in the error message");
    }

    public function testIfRequestHaveInValidesDatasResponseHaveA400StatusCode()
    {
        $this->client->request('POST',
            '/api/wsse/v1/user/create',
            array(),    // parameters
            array(),    // files
            array('HTTP_X-WSSE' => WsseProvider::makeToken(UsersFixtures::ADMIN_USERNAME, UsersFixtures::ADMIN_PASSWORD)), // custom http header, prefix with HTTP_ ans uppercased
            static::getUserDataInJson(array("first_name" => "", "email" => "unvalidemail"), 'last_name')
        );
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $return = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue(isset($return['last_name']), "missing data is in the error message");
        $this->assertTrue(isset($return['first_name']), "unvalid data is in the error message");
        $this->assertTrue(isset($return['email']), "unvalid data is in the error message");
    }


    
    public function testIfRequestHaveExistingUserEmailResponseHaveA403StatusCode()
    {
        $this->client->request('POST',
            '/api/wsse/v1/user/create',
            array(),    // parameters
            array(),    // files
            array('HTTP_X-WSSE' => WsseProvider::makeToken(UsersFixtures::ADMIN_USERNAME, UsersFixtures::ADMIN_PASSWORD)), // custom http header, prefix with HTTP_ ans uppercased
            static::getUserDataInJson(array("email" => UsersFixtures::USER_EMAIL))
        );
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
        $return = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue(isset($return['email']), "existing email is in the error message");
        $this->assertEquals("Cet email est déjà utilisé.", $return['email']);
    }

    public function testIfRequestHaveValideDatsResponseHaveA201StatusCode()
    {
        $this->client->request('POST',
            '/api/wsse/v1/user/create',
            array(),    // parameters
            array(),    // files
            array('HTTP_X-WSSE' => WsseProvider::makeToken(UsersFixtures::ADMIN_USERNAME, UsersFixtures::ADMIN_PASSWORD)), // custom http header, prefix with HTTP_ ans uppercased
            static::getUserDataInJson(array())
        );
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $return = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue(isset($return['userid']), "new created user information are return");
        $this->assertEquals(static::$UserFixtures['email'], $return['email']);
    }
    
    /**
     * Return user data with a json format
     *
     * @param array  $invalidValues Values to override expected to be invalid
     * @param string $dataToRemove  key Values to delete
     * 
     * @return string JSON fixture values
     */    
    public static function getUserDataInJson(array $invalidValues = array(), $dataToRemove = null)
    {
        $fixtures = static::getFixtures('User', $invalidValues);
        if ($dataToRemove 
                && isset($fixtures[$dataToRemove])
        ) {
            unset($fixtures[$dataToRemove]);
        }
        
        return json_encode($fixtures);
    }   
    
    /**
     * Common getter for all fixtures
     *
     * @param string $fixtureType
     * @param array  $invalidValues Values to override expected to be invalid
     * @param array  $validValues   Values to override expected to be valid
     */
    public static function getFixtures($fixtureType, array $invalidValues = array(), array $validValues = array())
    {
        switch ($fixtureType) {
            case 'User':
                $fixtureArray = static::$UserFixtures;
                break;
            default:
                throw new \Exception('This type of fixture is not implemented');
        }

        return array_merge($fixtureArray, $invalidValues, $validValues);
    }      
}
