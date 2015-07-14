<?php

namespace Sfynx\CrawlerBundle\Tests\Crawler;

use Sfynx\CrawlerBundle\Crawler\XmlCrawlerHelper;

/**
 * This class tests the XmlCrawler Class.
 */
class XmlCrawlerHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testPathIsLocalWithLocalPath()
    {
        $this->assertTrue(XmlCrawlerHelper::pathIsLocal(__DIR__.'/datas/validXmlTest.xml'));
    }

    public function testPathIsLocalWithRemotePath()
    {
        $this->assertFalse(XmlCrawlerHelper::pathIsLocal('http://domain.com/validXmlTest.xml'));
    }

    public function testFormatLibXmlErrors()
    {
        $xmlFile = __DIR__.'/datas/wrongFormatedXmlTest.xml';
        libxml_use_internal_errors(true);
        $xml = simplexml_load_file($xmlFile);
        $formatedErrors = XmlCrawlerHelper::formatLibXmlErrors(libxml_get_errors());
        $this->assertGreaterThan(2, count($formatedErrors));
        unset($xml);
    }
}
