<?php

namespace Sfynx\CrawlerBundle\Tests\Crawler;

use Sfynx\CrawlerBundle\Crawler\SogecVoucherCrawler;
use Sfynx\CrawlerBundle\Crawler\XmlCrawlerValidator;
use Sfynx\CrawlerBundle\Propel\Voucher;

/**
 * This class tests the User entity.
 */
class SogecVoucherCrawlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExtendXmlCrawler()
    {
        $xmlFile = __DIR__.'/datas/validXmlTest.xml';
        $voucherCrawler = new SogecVoucherCrawler($xmlFile);
        $this->assertInstanceOf('Sfynx\CrawlerBundle\Crawler\GenericCrawler', $voucherCrawler);
    }

    public function testDefaultConfiguration()
    {
        $xmlFile = __DIR__.'/datas/validXmlTest.xml';
        $voucherCrawler = new SogecVoucherCrawler($xmlFile);
        $configuration = $voucherCrawler->getConfiguration();
        $this->assertEmpty($configuration['secretKey']);
    }

    public function testOveridingDefaultConfiguration()
    {
        $xmlFile = __DIR__.'/datas/validXmlTest.xml';
        $options = array(
            'secretKey' => 'testSecretKey'
        );
        $voucherCrawler = new SogecVoucherCrawler($xmlFile, $options);
        $configuration = $voucherCrawler->getConfiguration();
        $this->assertEquals($configuration['secretKey'], 'testSecretKey');
    }

    public function testThatDistantUrlHasAValidToken()
    {
        $sogecUrl = "https://sfynx.pi-groupe/tchizbox_flux2013/index";
        $options = array(
            'createFolder' => true,
            'workingFolder' => __DIR__.'/xmlImported',
            'xmlLocalBaseName' => 'globalVoucher',
            'secretKey' => 'tokenForTest'
        );
        $voucherCrawler = new SogecVoucherCrawler($sogecUrl, $options);
        $this->assertEquals($voucherCrawler->getDistantUrl(), $sogecUrl);
        $voucherCrawler->getSimpleXml();
        $valideUrl = $sogecUrl . '/' . md5('tokenForTest'.date('Y-m-d'));
        $this->assertEquals($voucherCrawler->getDistantUrl(), $valideUrl);
        XmlCrawlerTestHelper::rrmdir(__DIR__.'/xmlImported');
    }

    public function testGetXmlDataInArray()
    {
        $xmlFile = __DIR__ . '/datas/validXmlTest.xml';
        $voucherCrawler = new SogecVoucherCrawler($xmlFile);
        $xsdFile = __DIR__.'/xsd/fluxGlobal.xsd';
        $validator = new XmlCrawlerValidator($xsdFile);
        $voucherCrawler->setValidator($validator);
        $dataInArray = $voucherCrawler->getXmlDataInArray();
        $this->assertEquals(2, count($dataInArray));
        $voucher = new Voucher();
        $voucher->fromArray($dataInArray[1]);
        $this->assertEquals(2, $voucher->getSogecId());
        $this->assertEquals(0.60, $voucher->getAmount());
        $this->assertEquals('https://sfynx.pi-groupe/images/imagesBr/truc.png', $voucher->getImgUrl());
        $this->assertEquals('Boite de 6 vache qui rit', $voucher->getDescription());
        $this->assertEquals('Vache qui rit', $voucher->getBrandName());
        $this->assertEquals('bundle', $voucher->getVoucherType());
    }
}
