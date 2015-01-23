<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage Tool
 * @package    Util
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-01-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Util;

use Sfynx\ToolBundle\Builder\PiCurlManagerBuilderInterface;

/**
 * Curl manager
 *
 * @subpackage Tool
 * @package    Util
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiCurlManager implements PiCurlManagerBuilderInterface 
{
    const TIME_OUT = 10;

    private $testMode = false;
    private $response;
    private $httpCode;
    private $defaultsOpt;
    private $curlInfo;
    private $options = array();
    private $url;

    /**
     * Initialisation du webservice
     *
     * @param string $psUrl    Url du ws
     * @param array  $paData   Data from
     * @param string $psMethod Méthode curl (GET/POST)
     */
    public function initialisation($psUrl, $paData, $psMethod)
    {
        $this->url = $psUrl;
        $laDefaultsOpt = array(
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => self::TIME_OUT
        );
        if ('GET' === $psMethod) {
            $laDefaultsOptMethod = array(
                CURLOPT_URL => $psUrl . (strpos($psUrl, '?') === false ? '?' : '') . http_build_query($paData),
            );
        } else {
            $laDefaultsOptMethod = array(
                CURLOPT_POST => 1,
                CURLOPT_URL => $psUrl,
                CURLOPT_FRESH_CONNECT => 1,
                CURLOPT_POSTFIELDS => http_build_query($paData)
            );
        }
        $this->defaultsOpt = $laDefaultsOpt + $laDefaultsOptMethod;
    }

    /**
     * Positionne le mode de test d'un webservice
     *
     */
    public function setTestmode()
    {
        $this->testMode = true;
    }

    /**
     * Rajout d'options curl
     *
     * @param array paOptions
     */
    public function setOpt(array $paOptions)
    {
        $this->options = $paOptions;
    }

    /**
     * execute curl
     *
     * @return string Réponse du ws
     */
    public function execute()
    {
        $loCurl = curl_init();
        curl_setopt_array($loCurl, ($this->options + $this->defaultsOpt));
        if (!$this->testMode) {
            if (!$result = curl_exec($loCurl)) {
                //trigger_error("Erreur lors de l'execution : " . $this->url ." \n". curl_error($loCurl));
                $result = "[NATEXO_DETECT_ERROR] : " . curl_error($loCurl);
            }
            $this->curlInfo = curl_getinfo($loCurl);
            $this->httpCode = '[' . $this->curlInfo['http_code'] . ']';
            $this->response = $result;
            curl_close($loCurl);
        } else {
            $this->httpCode = '[500]';
            $this->response = 'NATEXO_TEST_MODE';
        }
        
        return $this->getResponse();
    }

    /**
     * Getter du http code
     *
     * @return string
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * Getter du curl getinfo
     *
     * @return array
     */
    public function getCurlInfo()
    {
        return $this->curlInfo;
    }

    /**
     * Getter de la réponse du ws
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }
}
