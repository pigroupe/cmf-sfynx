<?php
/**
 * This file is part of the <Auth> project.
 *
 * @subpackage Auth
 * @package    Tests
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Client;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Process\Process;
use Sfynx\AuthBundle\DataFixtures\ORM\UsersFixtures;

/**
 * This is the base test case for all functional tests.
 * It bootstraps the database before each test class.
 *
 * @subpackage Auth
 * @package    Tests
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class WebTestCase extends BaseWebTestCase
{
    const URL_CONNECTION         = '/login';
    const URL_CONNECTION_CHECK   = '/login_check';
    const URL_CONNECTION_FAILURE = '/login_failure';
    const URL_DECONNECTION       = '/logout';
    
    /** @var Application */
    protected static $application;
    
    protected static $kernel;
    
    protected static $em;
    
    protected static $metadata;
    
    protected static $translator;
    
    protected $validator;
    
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        
        static::$kernel->getContainer()->get('request')->setLocale('en_EN');

        static::$em = static::$kernel->getContainer()->get('doctrine')->getManager();
        
        static::$translator = static::$kernel->getContainer()->get('translator');
        
        $schemaTool = new SchemaTool(static::$em);
        static::$metadata = static::$em->getMetadataFactory()->getAllMetadata();
    }    
    
    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new \Symfony\Component\Console\Input\StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new \Symfony\Bundle\FrameworkBundle\Console\Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }


    protected static function loadFixtures()
    {
        self::runCommand('doctrine:fixtures:load --append --fixtures=vendor/sfynx-project/auth-bundle/Sfynx/AuthBundle --env=test');
    }
    
    protected static function emptyDatabase()
    {
        self::runCommand('doctrine:database:drop --force --env=test');
        self::runCommand('doctrine:database:create --env=test');
        self::runCommand('doctrine:schema:create --env=test');
    }
    
    protected static function emptyCache()
    {
        $process = new Process("php app/console cache:clear --env=test");
        $process->setTimeout(2);
        $process->run();
    } 
    
    protected static function emptyLoginFailure()
    {
        $path_dir_login_failure = static::$kernel->getContainer()->getParameter('sfynx.auth.loginfailure.cache_dir');
        $path_dir_login_failure = realpath($path_dir_login_failure);        
        if (strlen($path_dir_login_failure)>= 2) {   
            //print_r("$path_dir_login_failure/*");
            $process = new Process("rm -rf $path_dir_login_failure/*");        
            $process->setTimeout(2);
            $process->run();
        }
    }     
    
    protected static function updateSchema()
    {
        self::runCommand('doctrine:schema:update --force --env=test');
    }     

    /**
     * @param Client  $client
     * @param boolean $is_redirection
     * 
     * @return Client
     */
    protected function loginRoleUser(
            Client $client = null, 
            $is_redirection = true, 
            $username = UsersFixtures::USER_USERNAME, 
            $password = UsersFixtures::USER_PASS,
            $role = '{"0":"ROLE_USER"}'
    ) {
        if (!$client) {
            $client = static::createClient();
        }
        $client->request('GET', self::URL_CONNECTION);
        $form = $client->getCrawler()->filter('form input[type=submit]')->first()->form();
        $client->submit(
            $form,
            array(
                'roles' => $role,
                '_username' => UsersFixtures::USER_USERNAME,
                '_password' => UsersFixtures::USER_PASS
            )
        );
        if ($is_redirection) {
            $client->followRedirect();
        }

        return $client;
    }

    /**
     * @param Client  $client
     * @param boolean $is_redirection
     * 
     * @return Client
     */
    protected function loginRoleAdmin(
            Client $client = null, 
            $is_redirection = true, 
            $username = UsersFixtures::ADMIN_USERNAME, 
            $password = UsersFixtures::ADMIN_PASS,
            $role = '{"0":"ROLE_ADMIN","1":"ROLE_SUPER_ADMIN"}'
    ) {
        if (!$client) {
            $client = static::createClient();
        }
        $client->request('GET', self::URL_CONNECTION);        
        
        $form = $client->getCrawler()->filter('form input[type=submit]')->first()->form();
        $client->submit(
            $form,
            array(
                'roles' => $role,
                '_username' => $username,
                '_password' => $password
            )
        );
        if ($is_redirection) {
            $client->followRedirect();
        }

        return $client;
    }

    /**
     * @param  \Symfony\Bundle\FrameworkBundle\Client $client
     * @return WebTestCase
     */
    protected function logout(Client $client)
    {
        $client->request('GET', static::URL_DECONNECTION);
        $client->followRedirect();

        return $this;
    }

    /**
     * Asserts that client's response is of given status code.
     * If not it traverses the response to find symfony error and display it.
     *
     * @param integer $statusCode
     * @param Client  $client
     */
    protected function assertStatusCode($statusCode, Client $client)
    {
        /** @var $response Response */
        $response = $client->getResponse();
        if ($response->isServerError() && $response->getStatusCode() >= 500 && $response->getStatusCode() < 600) {
            $this->assertEquals(
                $statusCode,
                $response->getStatusCode(),
                $client->getCrawler()->filter('.block_exception h1')->text()
            );
        } else {
            $this->assertEquals($statusCode, $response->getStatusCode());
        }
    }

    protected function assertSEOCompatible(Client $client, $type = 'article')
    {
        $crawler = $client->getCrawler();
        $url = $client->getRequest()->getUri();
        $title = $crawler->filter('title')->text();

        // title
        $this->assertNotEmpty($title, 'The title is empty.');
        // meta
        $this->assertCount(1, $crawler->filter('meta[charset=UTF-8]'));
        $this->assertCount(1, $crawler->filter('meta[property="og:title"][content="' . $title . '"]'));
        $this->assertCount(1, $crawler->filter('meta[property="og:type"][content="' . $type . '"]'));
        $this->assertCount(1, $crawler->filter('meta[property="og:url"][content="' . $url . '"]'));
        // img
        // $this->assertCount(0, $crawler->filter('img[alt=""]'));
        $crawler->filter('img:not([alt])')->each(function ($node, $i) {
            $this->assertTrue( false, 'An image with no alt attribute has been found src='. $node->attr('src'));
        });
    }

    protected function dumpCrawler($crawler)
    {
        foreach ($crawler as $dom) {
            print $dom->ownerDocument->saveHTML($dom) . PHP_EOL;
        }
    }

    protected function assertHasEventsCalled($profile, $event)
    {
        $calledEvents = $profile->getCollector('events')->getCalledListeners();
        $this->assertContains($event, implode(array_keys($calledEvents)));
    }
    
    protected function getValidator()
    {
        if (!$this->validator) {
            $client = static::createClient();
            $this->validator = $client->getContainer()->get('validator');
        }

        return $this->validator;
    }    
}
