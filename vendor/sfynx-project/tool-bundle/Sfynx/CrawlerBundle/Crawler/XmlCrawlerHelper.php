<?php
/**
 * This class provided several methods used for xml crawling
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
 * This class provided several methods used for xml crawling
 *
 * @author  Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @package Crawler
 */
class XmlCrawlerHelper
{
    /**
     * This method test if a file path is local or distant
     *
     * @param  string  $path path to file
     * @return boolean true if local, false if distant
     */
    public static function pathIsLocal($path)
    {
        $pattern = '/\b(?:(?:https?):\/\/|www\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i';
        if (preg_match($pattern, $path)) {
            return false;
        }

        return true;
    }

    /**
     * This function format libXMLError in a readeable way
     * http://ca2.php.net/manual/en/function.libxml-get-errors.php
     *
     * @param  array $errors an array of libXMLError
     * @return array an array of error in readable format, one entry by error
     */
    public static function formatLibXmlErrors($errors)
    {
        $formatedErrors = array();
        foreach ($errors as $error) {
            $return = str_repeat('-', $error->column) . "^\n";

            switch ($error->level) {
                case LIBXML_ERR_WARNING:
                    $return .= "Warning $error->code: ";
                    break;
                case LIBXML_ERR_ERROR:
                    $return .= "Error $error->code: ";
                    break;
                case LIBXML_ERR_FATAL:
                    $return .= "Fatal Error $error->code: ";
                    break;
            }
            $return .= trim($error->message) .
               "\n  Line: $error->line" .
               "\n  Column: $error->column";
            $formatedErrors[] = $return;
        }

        return $formatedErrors;
    }
}
