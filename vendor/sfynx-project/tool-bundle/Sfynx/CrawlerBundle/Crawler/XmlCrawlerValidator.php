<?php
/**
 * This class is used to validate an imported xml file from an XmlCrawler object
 * with an XmlSchema file
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
 * This class is used to validate an imported xml file from an XmlCrawler object
 * with an XmlSchema file
 *
 * @author  Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @package Crawler
 */
class XmlCrawlerValidator
{
    protected $configuration;
    protected $localXsd = null;
    protected $errors = array();

    /**
     * Class constructor
     *
     * @param string $xsdFile path to the xsd
     * @param array  $options an array of parameters overload the default configuration
     */
    public function __construct($xsdFile, $options = array())
    {
        //@todo gerer le cas ou le xsd est distant - pas besoin pour le moment
        //@todo permettre de trouver dans le xml à tester le path vers le xsd
        if (!XmlCrawlerHelper::pathIsLocal($xsdFile)) {
            throw new XmlCrawlerException('The remote xsd are not yet manage in this version of XmlCrawlerValidator');
        }
        if (!file_exists($xsdFile)) {
            throw new XmlCrawlerException('Can not find '. $xsdFile);
        }
        $this->localXsd = $xsdFile;
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
     * This function test the validity of the xml file
     *
     * @param string $xmlToTest path to the xml to validate
     */
    public function xmlIsValid($xmlToTest)
    {
        $xml = new \DOMDocument();
        $xml->load($xmlToTest);

        libxml_use_internal_errors(true);
        if (!$xml->schemaValidate($this->localXsd)) {
            $this->errors = XmlCrawlerHelper::formatLibXmlErrors(libxml_get_errors());
            libxml_clear_errors();

            return false;
        }

        return true;
    }

    /**
     * This method return errors generated during processing of the xml source validation
     *
     * @return array errors generated during processing of the xml source validation
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * this function set default configuration parameters
     */
    private function setDefaultConfiguration()
    {
        $this->configuration = array(
            'createFolder' => false,
            'workingFolder' => null,
            'xsdLocalBaseName' => 'xsdImported',
            'archiveError' => false,
            'archiveTimestamp' => 'date', //should be date or datetime
            'archiveNumber' => 7
        );
    }

    /**
     * This method overide default configuration parameters with parameters passed in $options from constructor
     *
     * @param array $options parameters passed in $options from constructor
     */
    private function overideConfiguration($options)
    {
        foreach ($options as $optionKey => $optionValue) {
            if (array_key_exists($optionKey, $this->configuration)) {
                $this->configuration[$optionKey] = $optionValue;
            }
        }
    }
}
