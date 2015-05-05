<?php
/**
 * This file is part of the <Captcha> project.
 *
 * @category   Captcha
 * @package    Controller
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
namespace Sfynx\CaptchaBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Api Controller for captcha picture
 * 
 * @category   Captcha
 * @package    Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 * 
 * @Route("/sfynx/captcha")
 */
class ApiController extends ContainerAware
{
    /**
     * Get binary
     *
     * @param Request $request
     * @param string  $key
     * 
     * @Route("/media/{key}", name="sfynx_captcha_api_get")
     * @Method({"GET"})
     */
    public function getBinaryAction(Request $request, $key)
    {
        $response = new Response();
        try {
            $captchapictures = $this->container->get("sfynx.captcha.manager")->getPicturesInSession();   
            $file = $captchapictures[$key];
            
            $response->setPublic();
            $response->setStatusCode(200);
            $response->headers->set('Content-Description', "File transfert");
            $response->headers->set('Content-Type', 'application/octet-stream');
            $response->headers->set('Content-Disposition', 'inline; filename='.basename($file));
            $response->headers->set('Content-Length', filesize($file));
            $response->headers->set('Cache-Control', "must-revalidate");
            $response->headers->set('Expires', '0');
            $response->setContent(readfile($file));           
        } catch (\Exception $e) {
            $response->setStatusCode(404);
            $response->setContent($e->getMessage());
            $response->headers->set('Content-Type', 'text/html');
        }

        return $response;
    }
}
