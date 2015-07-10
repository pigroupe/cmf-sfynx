<?php

namespace Sfynx\CrawlerBundle\Tests\Crawler;

use Sfynx\CrawlerBundle\Crawler\XmlCrawler;
use Sfynx\CrawlerBundle\Crawler\XmlCrawlerValidator;

/**
 * This class tests the XmlCrawler Class.
 */
class XmlCrawlerTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        $testWorkingDir = __DIR__.'/xmlImported';
        if (file_exists($testWorkingDir)) {
            XmlCrawlerTestHelper::rrmdir($testWorkingDir);
        }
    }

    /**
     * @expectedException Sfynx\CrawlerBundle\Crawler\XmlCrawlerException
     */
    public function testLocalFileUnavailable()
    {
        $xmlFile = __DIR__.'/validXmlTest.xml';
        $crawler = new XmlCrawler($xmlFile);
        unset($crawler);
    }

    public function testOverideConfigurationWithWorkingFolderToCreateWithLocalFile()
    {
        $options = array(
            'createFolder' => true,
            'workingFolder' => __DIR__.'/xmlImported',
            'xmlLocalBaseName' => 'xmlImportedOverided'
        );
        $xmlFile = __DIR__.'/datas/validXmlTest.xml';
        $crawler = new XmlCrawler($xmlFile, $options);
        $configuration = $crawler->getConfiguration();
        $this->assertTrue($configuration['createFolder']);
        $this->assertEquals(__DIR__.'/xmlImported', $configuration['workingFolder']);
        $this->assertEquals($configuration['xmlLocalBaseName'], 'xmlImportedOverided');
        $this->assertFalse(file_exists(__DIR__.'/xmlImported'));
    }

    public function testOverideConfigurationWithWorkingFolderToCreateWithDistantFile()
    {
        $options = array(
            'createFolder' => true,
            'workingFolder' => __DIR__.'/xmlImported',
            'xmlLocalBaseName' => 'xmlImportedOverided'
        );
        $xmlFile = 'http://datas/validXmlTest.xml';
        $crawler = new XmlCrawler($xmlFile, $options);
        $configuration = $crawler->getConfiguration();
        $this->assertTrue($configuration['createFolder']);
        $this->assertEquals(__DIR__.'/xmlImported', $configuration['workingFolder']);
        $this->assertEquals($configuration['xmlLocalBaseName'], 'xmlImportedOverided');
        $this->assertTrue(file_exists(__DIR__.'/xmlImported'));
    }

    /**
     * @expectedException Sfynx\CrawlerBundle\Crawler\XmlCrawlerException
     */
    public function testOverideConfigurationWithUnexistingWorkingFolderWithCreateFolderFalseAndDistantXml()
    {
        $options = array(
            'createFolder' => false,
            'workingFolder' => __DIR__.'/xmlImportedTest',
            'xmlLocalBaseName' => 'xmlImported'
        );
        $xmlFile = 'http://datas/validXmlTest.xml';
        $crawler = new XmlCrawler($xmlFile, $options);
        unset($crawler);
    }

    /**
     * @expectedException Sfynx\CrawlerBundle\Crawler\XmlCrawlerException
     */
    public function testOverideConfigurationWithWorkingFolderUnwritableAndDistantXml()
    {
        mkdir(__DIR__.'/xmlImported', 0555);
        $options = array(
            'createFolder' => false,
            'workingFolder' => __DIR__.'/xmlImported',
            'xmlLocalBaseName' => 'xmlImported'
        );
        $xmlFile = 'http://datas/validXmlTest.xml';
        $crawler = new XmlCrawler($xmlFile, $options);
        unset($crawler);
    }

    /**
     * @expectedException Sfynx\CrawlerBundle\Crawler\XmlCrawlerException
     */
    public function testDistantXmlWithoutOverideWorkingFolderConfiguration()
    {
        $xmlFile = 'http://domain.com/validXmlTest.xml';
        $crawler = new XmlCrawler($xmlFile);
        unset($crawler);
    }

    public function testOverideConfigurationWithAnEmptyParameter()
    {
        $xmlFile = __DIR__.'/datas/validXmlTest.xml';
        $options = array(
            'xmlLocalBaseName' => ''
        );
        $crawler = new XmlCrawler($xmlFile, $options);
        $configuration = $crawler->getConfiguration();
        $this->assertEquals($configuration['xmlLocalBaseName'], 'xmlImported');
    }

    public function testDefaultConfiguration()
    {
        $xmlFile = __DIR__.'/datas/validXmlTest.xml';
        $crawler = new XmlCrawler($xmlFile);
        $configuration = $crawler->getConfiguration();
        $this->assertFalse($configuration['createFolder']);
        $this->assertEmpty($configuration['workingFolder']);
        $this->assertEquals($configuration['xmlLocalBaseName'], 'xmlImported');
    }

    public function testUnattainableDistantXml()
    {
        $options = array(
            'createFolder' => true,
            'workingFolder' => __DIR__.'/xmlImported',
            'xmlLocalBaseName' => 'xmlImported'
        );
        $xmlFile = 'http://www.google.com/qsjkdhgh/donteattheyellowsnow.xml';
        $crawler = new XmlCrawler($xmlFile, $options);
        $this->assertFalse($crawler->getSimpleXml());
        $errors = $crawler->getErrors();
        $this->assertTrue(isset($errors['getDistantXml']));
    }

    public function testThatLocalXmlIsNotDeleteOnDestructWithLocalSource()
    {
        $xmlFile = __DIR__.'/datas/validXmlTest.xml';
        $crawler = new XmlCrawler($xmlFile);
        $crawler->getSimpleXml();
        unset($crawler);
        $this->assertTrue(file_exists($xmlFile));
    }

    /*public function testExistingDistantXml()
    {
        $this->markTestSkipped('I prefere skip this test that use a distant xml not our own.');
        $options = array(
            'createFolder' => true,
            'workingFolder' => __DIR__.'/xmlImported',
            'xmlLocalBaseName' => 'xmlFromLastFm'
        );
        $xmlFile = 'http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks
                                                &user=alexisjanvier&api_key=29daf3379620b3c7fb03c0cd5d1307e6';
        $crawler = new XmlCrawler($xmlFile, $options);
        $this->assertInstanceOf('\SimpleXMLElement', $crawler->getSimpleXml());
        $this->assertTrue(file_exists(__DIR__.'/xmlImported' . '/xmlFromLastFm.xml'));
    }

    public function testThatLocalXmlIsDeleteOnDestructWithDistantSource()
    {
        $options = array(
            'createFolder' => true,
            'workingFolder' => __DIR__.'/xmlImported',
            'xmlLocalBaseName' => 'xmlFromLastFm'
        );
        $xmlFile = 'http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks
                                                &user=alexisjanvier&api_key=29daf3379620b3c7fb03c0cd5d1307e6';
        $crawler = new XmlCrawler($xmlFile, $options);
        $crawler->getSimpleXml();
        $this->assertTrue(file_exists(__DIR__.'/xmlImported' . '/xmlFromLastFm.xml'));
        unset($crawler);
        $this->assertFalse(file_exists(__DIR__.'/xmlImported' . '/xmlFromLastFm.xml'));
    }*/

    public function testGetSimpleXmlWithWrongFormattingXml()
    {
        $xmlFile = __DIR__.'/datas/wrongFormatedXmlTest.xml';
        $crawler = new XmlCrawler($xmlFile);
        $this->assertFalse($crawler->getSimpleXml());
        $errors = $crawler->getErrors();
        $this->assertTrue(isset($errors['badFormat']));
    }

    public function testGetSimpleXmlWithoutValidationWithUnvalidXml()
    {
        $xmlFile = __DIR__.'/datas/unvalidXmlTest.xml';
        $crawler = new XmlCrawler($xmlFile);
        $this->assertInstanceOf('\SimpleXMLElement', $crawler->getSimpleXml());
    }

    public function testGetSimpleXmlWithValidationAndUnvalidXmlFile()
    {
        $xsdFile = __DIR__.'/xsd/fluxGlobal.xsd';
        $validator = new XmlCrawlerValidator($xsdFile);
        $xmlFile = __DIR__.'/datas/unvalidXmlTest.xml';
        $crawler = new XmlCrawler($xmlFile);
        $crawler->setValidator($validator);
        $this->assertFalse($crawler->getSimpleXml());
        $errors = $crawler->getErrors();
        $this->assertTrue(isset($errors['xmlNotValide']));
    }

    public function testGetSimpleXmlWithValidationAndValidFile()
    {
        $xsdFile = __DIR__.'/xsd/fluxGlobal.xsd';
        $validator = new XmlCrawlerValidator($xsdFile);
        $xmlFile = __DIR__.'/datas/validXmlTest.xml';
        $crawler = new XmlCrawler($xmlFile);
        $crawler->setValidator($validator);
        $this->assertInstanceOf('\SimpleXMLElement', $crawler->getSimpleXml());
        $errors = $crawler->getErrors();
        $this->assertFalse(isset($errors['xmlNotValide']));
    }
}
