<?php

namespace Sfynx\CrawlerBundle\Tests\Crawler;

use Sfynx\CrawlerBundle\Tests\Crawler\Crawler\ExCrawler;
use Sfynx\CrawlerBundle\Crawler\XmlCrawlerValidator;
use Sfynx\CrawlerBundle\Propel\Voucher;

/**
 * This class tests the User entity.
 */
class CrawlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExtendXmlCrawler()
    {
        $xmlFile = __DIR__.'/datas/validXmlTest.xml';
        $objCrawler = new ExCrawler($xmlFile);
        $this->assertInstanceOf('Sfynx\CrawlerBundle\Crawler\GenericCrawler', $objCrawler);
    }

    public function testDefaultConfiguration()
    {
        $xmlFile = __DIR__.'/datas/validXmlTest.xml';
        $objCrawler = new ExCrawler($xmlFile);
        $configuration = $objCrawler->getConfiguration();
        $this->assertEmpty($configuration['secretKey']);
    }

    public function testOveridingDefaultConfiguration()
    {
        $xmlFile = __DIR__.'/datas/validXmlTest.xml';
        $options = array(
            'secretKey' => 'testSecretKey'
        );
        $objCrawler = new ExCrawler($xmlFile, $options);
        $configuration = $objCrawler->getConfiguration();
        $this->assertEquals($configuration['secretKey'], 'testSecretKey');
    }

    public function testThatDistantUrlHasAValidToken()
    {
        $Url = "https://sfynx.pi-groupe/tchizbox_flux2013/index";
        $options = array(
            'createFolder' => true,
            'workingFolder' => __DIR__.'/xmlImported',
            'xmlLocalBaseName' => 'globalVoucher',
            'secretKey' => 'tokenForTest'
        );
        $objCrawler = new ExCrawler($sogecUrl, $options);
        $this->assertEquals($objCrawler->getDistantUrl(), $Url);
        $objCrawler->getSimpleXml();
        $valideUrl = $Url . '/' . md5('tokenForTest'.date('Y-m-d'));
        $this->assertEquals($objCrawler->getDistantUrl(), $valideUrl);
        XmlCrawlerTestHelper::rrmdir(__DIR__.'/xmlImported');
    }

    public function testGetXmlDataInArray()
    {
        $xmlFile = __DIR__ . '/datas/validXmlTest.xml';
        $objCrawler = new ExCrawler($xmlFile);
        $xsdFile = __DIR__.'/xsd/fluxGlobal.xsd';
        $validator = new XmlCrawlerValidator($xsdFile);
        $objCrawler->setValidator($validator);
        $dataInArray = $objCrawler->getXmlDataInArray();
        $this->assertEquals(2, count($dataInArray));
    }
}
