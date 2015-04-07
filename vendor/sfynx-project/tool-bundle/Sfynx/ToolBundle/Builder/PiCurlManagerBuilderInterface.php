<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   Tool
 * @package    Util
 * @subpackage Builder
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Builder;

/**
 * Curl builder interface.
 *
 * @category   Tool
 * @package    Util
 * @subpackage Builder
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
interface PiCurlManagerBuilderInterface
{
    /**
     * Initialisation du webservice
     *
     * @param string $psUrl    Url du ws
     * @param array  $paData   Data from
     * @param string $psMethod Méthode curl (GET/POST)
     */
    public function initialisation($psUrl, $paData, $psMethod);
    
    /**
     * Positionne le mode de test d'un webservice
     *
     */
    public function setTestmode();
    
    /**
     * Rajout d'options curl
     *
     * @param array paOptions
     */
    public function setOpt(array $paOptions);
    
    /**
     * execute curl
     *
     * @return string Réponse du ws
     */
    public function execute();
    
    /**
     * Getter du http code
     *
     * @return string
     */
    public function getHttpCode();
    
    /**
     * Getter du curl getinfo
     *
     * @return array
     */
    public function getCurlInfo();
    
    /**
     * Getter de la réponse du ws
     *
     * @return string
     */
    public function getResponse();    
}
