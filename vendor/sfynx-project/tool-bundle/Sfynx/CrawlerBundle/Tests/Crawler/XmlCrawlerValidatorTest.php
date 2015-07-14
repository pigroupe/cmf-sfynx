<?php

namespace Sfynx\CrawlerBundle\Tests\Crawler;

use Sfynx\CrawlerBundle\Crawler\XmlCrawlerValidator;

/**
 * This class tests the XmlCrawlerValidator Class.
 */
class XmlCrawlerValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Sfynx\CrawlerBundle\Crawler\XmlCrawlerException
     */
    public function testDistantXsd()
    {
        $xsdFile = 'http://domain.com/xsdTest.xsd';
        $validator = new XmlCrawlerValidator($xsdFile);
        unset($validator);
    }

    /**
     * @expectedException \Sfynx\CrawlerBundle\Crawler\XmlCrawlerException
     */
    public function testLocalFileUnavailable()
    {
        $xsdFile = __DIR__ . '/fluxGlobal.xsd';
        $validator = new XmlCrawlerValidator($xsdFile);
        unset($validator);
    }

    public function testDefaultConfiguration()
    {
        $xsdFile = __DIR__.'/xsd/fluxGlobal.xsd';
        $validator = new XmlCrawlerValidator($xsdFile);
        $configuration = $validator->getConfiguration();
        $this->assertFalse($configuration['createFolder']);
        $this->assertEmpty($configuration['workingFolder']);
        $this->assertEquals($configuration['xsdLocalBaseName'], 'xsdImported');
        $this->assertFalse($configuration['archiveError']);
        $this->assertEquals($configuration['archiveTimestamp'], 'date');
        $this->assertEquals($configuration['archiveNumber'], 7);
    }

    public function testUnvalidXml()
    {
        $xsdFile = __DIR__.'/xsd/fluxGlobal.xsd';
        $validator = new XmlCrawlerValidator($xsdFile);
        $xmlFile = __DIR__.'/datas/unvalidXmlTest.xml';
        $this->assertFalse($validator->xmlIsValid($xmlFile));
        $errors = $validator->getErrors();
        $this->assertGreaterThan(0, count($errors));
    }

    public function testValidXml()
    {
        $xsdFile = __DIR__.'/xsd/fluxGlobal.xsd';
        $validator = new XmlCrawlerValidator($xsdFile);
        $xmlFile = __DIR__.'/datas/validXmlTest.xml';
        $this->assertTrue($validator->xmlIsValid($xmlFile));
        $errors = $validator->getErrors();
        $this->assertEquals(0, count($errors));
    }
}
