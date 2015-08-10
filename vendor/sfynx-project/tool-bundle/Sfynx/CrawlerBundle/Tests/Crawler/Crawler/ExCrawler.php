<?php
namespace Sfynx\CrawlerBundle\Tests\Crawler\Crawler;

use Sfynx\CrawlerBundle\Crawler\GenericCrawler;

class ExCrawler extends GenericCrawler
{

    /**
     * this method overdide parent method to create a valid distant path, with a valid token
     *
     * @return \SimpleXml source Xml as SimpleXml Object
     */
    public function getSimpleXml()
    {
        if ($this->distantXml !== null) {
            $this->prepareDistantUrl();
        }

        return parent::getSimpleXml();
    }

    /**
     * this method parse xml to set data in array, ready to be set on an Object.
     *
     * @return array datas ready to be set on on Voucher Object
     */
    public function getXmlDataInArray()
    {
        $dataInArray = array();
        $xml = $this->getSimpleXml();
        if (!$xml) {
            return false;
        }
        foreach ($xml->coupon as $coupon) {
            $objectData = array();
            $objectData['SogecId'] = (integer) $coupon->id;
            $objectData['Amount'] = (float) $coupon->montant;
            $objectData['ImgUrl'] = (string) $coupon->img_url;
            $objectData['Description'] = (string) $coupon->text;
            $objectData['BrandName'] = (string) $coupon->marque;
            $objectData['VoucherType'] = (string) $coupon->type;
            $objectData['Status'] = (string) $coupon->statut;
            $dataInArray[] = $objectData;
        }

        return $dataInArray;
    }

    /**
     * this method return the value of attribute distantXml
     * It's only used on test
     *
     * @return string distant xml url
     */
    public function getDistantUrl()
    {
        return $this->distantXml;
    }

    /**
     * this function set default configuration parameters
     * SogecVoucher Must have one additional parameter : a key used to create a valid token
     * used in distantXml url
     */
    protected function setDefaultConfiguration()
    {
        parent::setDefaultConfiguration();
        $this->configuration['secretKey'] = '';
    }

    /**
     * this method add a valid token to Sogec distant url
     *
     */
    private function prepareDistantUrl()
    {
        $valideToken = md5($this->configuration['secretKey'] . date('Y-m-d'));
        $this->distantXml .= '/' . $valideToken;
    }
}
