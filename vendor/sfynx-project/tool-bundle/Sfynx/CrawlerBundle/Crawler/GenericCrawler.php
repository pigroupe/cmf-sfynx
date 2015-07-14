<?php
/**
 * This abstract class is used to return a valid SimpleXml object
 * from an xml file that could be local or distant.
 * It's possible to add an XmlCrawlerValidator to validated the source xml with an Xsd file
 * It's also possible to generate archive of imported xml files with adding an XmlCrawlerArchiver object
 *
 * @category   Xml
 * @package    Crawler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */

namespace Sfynx\CrawlerBundle\Crawler;

/**
 * This generic class is used to return a valid SimpleXml object
 * from an xml file that could be local or distant.
 * It's possible to add an XmlCrawlerValidator to validated the source xml with an Xsd file
 * It's also possible to generate archive of imported xml files with adding an XmlCrawlerArchiver object
 *
 * @author  Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @package Crawler
 */
abstract class GenericCrawler
{
    protected $configuration;
    protected $localXml = null;
    protected $distantXml = null;
    protected $validator = null;
    protected $archiver = null;
    protected $errors = array();
    protected $simpleXml;

    /**
     * Class constructor
     *
     * @param string $xmlFile path to the xml
     * @param array  $options an array of parameters overload the default configuration
     */
    public function __construct($xmlFile, $options = array())
    {
        $this->setDefaultConfiguration();
        $this->overideConfiguration($options);
        if (!XmlCrawlerHelper::pathIsLocal($xmlFile)) {
            $this->validWorkingFolder();
            $this->validCurl();
            $this->distantXml = $xmlFile;
        } elseif (!file_exists($xmlFile)) {
            throw new XmlCrawlerException('Can not find '. $xmlFile);
        } else {
            $this->localXml = $xmlFile;
        }
        $this->setDefaultConfiguration();
        $this->overideConfiguration($options);
    }

    /**
     * This method return configuration's parameters
     *
     * @return array configuration's parameters used by object after instantiation
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * This method return errors generated during processing of the xml source
     *
     * @return array errors generated during processing of the xml source
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * this method set a XmlCrawlerValidator object that will be used ti validate local xml file
     * before return it. This is an option
     *
     * @param Sfynx\CrawlerBundle\Crawler\XmlCrawlerValidator $validator validator object
     */
    public function setValidator(XmlCrawlerValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * this method set a XmlCrawlerArchiver object that will archive xml file after parsing.
     * This is an option
     *
     * @param Sfynx\CrawlerBundle\Crawler\XmlCrawlerArchiver $validator validator object
     */
    public function setArchiver(XmlCrawlerArchiver $archiver)
    {
        $this->archiver = $archiver;
    }

    /**
     * this method return source Xml as SimpleXml Object if it's possible, false if not.
     * It should be false if source xml is distant and unattainable, or xml is incorrectly formatted,
     * or not validate by xsd file (via an XmlCrawlerValidator)
     *
     * @return \SimpleXml source Xml as SimpleXml Object
     */
    public function getSimpleXml()
    {
        if ($this->localXml === null && $this->distantXml !== null) {
            $this->getDistantXmlWithCurl();
        }

        if ($this->localXml === null) {
            $this->errors['localXml'] = 'localXml is null';

            return false;
        }
        if ($this->validator !== null && !$this->validator->xmlIsValid($this->localXml)) {
            $this->errors['xmlNotValide'] = $this->validator->getErrors();

            return false;
        }
        libxml_use_internal_errors(true);
        if (!$this->simpleXml = simplexml_load_file($this->localXml)) {
            $this->errors['badFormat'] = XmlCrawlerHelper::formatLibXmlErrors(libxml_get_errors());
            libxml_clear_errors();

            return false;
        }
        //@todo use XmlCrawlerArchiver if it's set.
        return $this->simpleXml;
    }

    /**
     * This method overide default configuration parameters with parameters passed in $options from constructor
     *
     * @param array $options parameters passed in $options from constructor
     */
    public function overideConfiguration($options)
    {
        foreach ($options as $optionKey => $optionValue) {
            if (array_key_exists($optionKey, $this->configuration)
                && (is_bool($optionValue) || trim($optionValue) != "")) {
                $this->configuration[$optionKey] = $optionValue;
            }
        }
    }

    /**
     * this method delete importedfile if source xml was distant
     * if we want de keep imported distant file, we have to set an XmlCrawlerArchiver
     *
     */
    public function __destruct()
    {
        if ($this->distantXml !== null && file_exists($this->localXml)) {
            unlink($this->localXml);
        }
    }

    /**
     * this method must return an array of arrays, ready to be set on a specific propel object
     *
     * @return array array of arrays, ready to be set on a specific propel object
     * @abstract
     */
    abstract public function getXmlDataInArray();

    /**
     * this function set default configuration parameters
     */
    protected function setDefaultConfiguration()
    {
        $this->configuration = array(
            'createFolder' => false,
            'workingFolder' => null,
            'xmlLocalBaseName' => 'xmlImported',
            'verifyCertificates' => true
        );
    }

    /**
     *
     * @throws XmlCrawlerException
     */
    private function validCurl()
    {
        if (!in_array('curl', get_loaded_extensions())) {
            throw new XmlCrawlerException('Curl must be loaded before use distant Xml');
        }
    }

    private function validWorkingFolder()
    {
        if (!file_exists($this->configuration['workingFolder']) && $this->configuration['createFolder']) {
            mkdir($this->configuration['workingFolder'], 0777);
        }
        if (!is_writable($this->configuration['workingFolder'])) {
            throw new XmlCrawlerException('WorkingFolder ('.$this->configuration['workingFolder'].') must be writable');
        }
    }

    private function getDistantXmlWithCurl()
    {
        set_time_limit(0);
        $destination = $this->configuration['workingFolder'] . '/' . $this->configuration['xmlLocalBaseName'] . '.xml';
        $importedXml = fopen($destination, 'w+');
        $curlSession = curl_init($this->distantXml);
        curl_setopt($curlSession, CURLOPT_TIMEOUT, 50);
        curl_setopt($curlSession, CURLOPT_FILE, $importedXml);
        curl_setopt($curlSession, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curlSession, CURLOPT_HEADER, false);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, $this->configuration['verifyCertificates']);
        $data = curl_exec($curlSession);
        $nbCurlErrors = curl_errno($curlSession);
        $CurlErrors = curl_error($curlSession);
        $http_status = curl_getinfo($curlSession, CURLINFO_HTTP_CODE);
        curl_close($curlSession);
        fwrite($importedXml, $data);
        fclose($importedXml);
        if ($http_status == 200
            && !$nbCurlErrors
            && file_exists($destination)
            && filesize($destination)) {
            $this->localXml = $destination;

            return true;
        } elseif ($nbCurlErrors) {
            $this->errors['getDistantXml'] = $CurlErrors;
        } elseif ($http_status != 200) {
            $this->errors['getDistantXml'] = 'Http response code : ' . $http_status;
        } else {
             $this->errors['getDistantXml'] = 'Error while writing local Xml';
        }

        return false;
    }
}
