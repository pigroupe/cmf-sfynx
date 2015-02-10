<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage Cmf
 * @package    Controller
 * @abstract
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-10-01
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sfynx\CoreBundle\Controller\abstractController;
use Sfynx\ToolBundle\Exception\ControllerException;

/**
 * abstract controller.
 *
 * @subpackage   Cmf
 * @package    Controller
 * @abstract
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class CmfabstractController extends abstractController
{
    /**
     * Deletes a entity.
     * 
     * @param string $type ['widget', 'page']
     * 
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deletetwigcacheajaxAction($type)
    {
    	$request = $this->container->get('request');
    	$em      = $this->getDoctrine()->getManager();    	 
    	if ($request->isXmlHttpRequest()) {
            $data        = $request->get('data', null);
            $new_data    = null;
            foreach ($data as $key => $value) {
                $values     = explode('_', $value);
                $id         = $values[2];
                $position   = $values[0];
                $new_data[$key] = array('position'=>$position, 'id'=>$id);
                $new_pos[$key]  = $position;
            }
            array_multisort($new_pos, SORT_ASC, $new_data);
            // get all locales
            $all_locales = $this->container->get('sfynx.auth.locale_manager')->getAllLocales();
            //
            foreach ($new_data as $key => $value) {
                if ($type == "widget") {
                        $entity = $em->getRepository("SfynxCmfBundle:Widget")->find($value['id']);
                } elseif ($type == "page") {
                    $entity = $value['id'];
                } else {
                    throw ControllerException::callAjaxOnlySupported('deleteajax');
                }
                foreach ($all_locales as $lang_page) {
                    if ($type == "widget") {
                        $this->container->get('pi_app_admin.manager.page')->cacheRefreshWidget($entity, $lang_page);
                    } elseif ($type == "page") {
                        $this->container->get('pi_app_admin.manager.page')->cacheRefreshPage($entity, $lang_page);
                    }
                }
            }
            // we disable all flash message
            $this->container->get('session')->getFlashBag()->clear();
            // we encode results
            $tab= array();
            $tab['id'] = '-1';
            $tab['error'] = '';
            $tab['fieldErrors'] = '';
            $tab['data'] = '';
            $response = new Response(json_encode($tab));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
    	} else {
            throw ControllerException::callAjaxOnlySupported('deleteajax');
    	}
    }    
}
