<?php
/**
 * This generic class is used to return a valid SimpleXml object
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
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @package Crawler
 */
class XmlCrawler extends GenericCrawler
{
    /**
     * this method must return an array of arrays, ready to be set on a specific propel object
     *
     * @throw XmlCrawlerException this method is not implemented in XmlCrawler Class
     *
     */
    public function getXmlDataInArray()
    {
        throw new XmlCrawlerException('this method is not implemented in XmlCrawler Class');
    }
}
