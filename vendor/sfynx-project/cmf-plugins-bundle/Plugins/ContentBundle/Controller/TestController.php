<?php
/**
 * This file is part of the <PI_CRUD> project.
 *
 * @category   PI_CRUD_Controllers
 * @package    Controller
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since XXXX-XX-XX
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Plugins\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sfynx\CoreBundle\Controller\abstractController;
use Sfynx\ToolBundle\Exception\ControllerException;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Plugins\ContentBundle\Entity\Test;
use Plugins\ContentBundle\Entity\BlocGeneral;
use Plugins\ContentBundle\Form\TestType;
use Plugins\ContentBundle\Entity\Translation\TestTranslation;

use Symfony\Component\Form\FormError;

/**
 * Test controller.
 *
 *
 * @category   PI_CRUD_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class TestController extends abstractController
{
    protected $_entityName = "PluginsContentBundle:Test";

    /**
     * Enabled Test entities.
     *
     * @Route("/admin/gedmo/test/enabled", name="admin_gedmo_test_enabledentity_ajax")
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *     
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function enabledajaxAction()
    {
       	return parent::enabledajaxAction();
    }

    /**
     * Disable Test entities.
     * 
     * @Route("/admin/gedmo/test/disable", name="admin_gedmo_test_disablentity_ajax")
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *     
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function disableajaxAction()
    {
       	return parent::disableajaxAction();
    } 

    /**
     * Change the position of a Test entity.
     *
     * @Route("/admin/gedmo/test/position", name="admin_gedmo_test_position_ajax")
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *     
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function positionajaxAction()
    {
       	return parent::positionajaxAction();
    }   

    /**
     * Delete a Test entity.
     *
     * @Route("/admin/gedmo/test/delete", name="admin_gedmo_test_deletentity_ajax")
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *     
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteajaxAction()
    {
       	return parent::deletajaxAction();
    }   

    /**
     * archive Test entities.
     *
     * @Route("/admin/gedmo/test/archive", name="admin_gedmo_test_archiventity_ajax")
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *     
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function archiveajaxAction()
    {
       	return parent::archiveajaxAction();
    }
    
    /**
     * Lists all Test entities.
     *
     * @Secure(roles="IS_AUTHENTICATED_ANONYMOUSLY")
	 * @return \Symfony\Component\HttpFoundation\Response
     *
	 * @access	public
	 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>   
     */
    public function indexAction()
    {
        $request    = $this->container->get('request');
    	$em        = $this->getDoctrine()->getManager();
    	$locale    = $this->container->get('request')->getLocale();
        
        $category    = $this->container->get('request')->query->get('category');
        $NoLayout    = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout) 	$template = "index.html.twig"; else $template = "index.html.twig";
        
        if ($NoLayout){
    		$query    = $em->getRepository("PluginsContentBundle:Test")->setContainer($this->container)->getAllByCategory($category, null, '', 'DESC', false, true, true);
    	} else {
    		$query    = $em->getRepository("PluginsContentBundle:Test")->getAllByCategory($category, null, '', 'ASC', false, true, true);
    	}
    	
        $is_Server_side = false;
        
        if ($request->isXmlHttpRequest() && $is_Server_side) {
           $aColumns    = array('a.position','a.id','a.status','m.name','a.published_at','a.enabled');
           $q1 = clone $query;
           $q2 = clone $query;
           $result    = $this->createAjaxQuery('select',$aColumns, $q1, 'a');
           $total    = $this->createAjaxQuery('count',$aColumns, $q2, 'a');
        
           $output = array(
               "sEcho" => intval($request->get('sEcho')),
                "iTotalRecords" => $total,
                "iTotalDisplayRecords" => $total,
                "aaData" => array()
           );
        
           foreach ($result as $e) {
              $row = array();
              $row[] = $e->getPosition();
              $row[] = $e->getId();
              
              if (is_object($e->getCategory())) {
                  $row[] = $e->getCategory()->getName();
              } else {
                  $row[] = "";
              }
              
              if (is_object($e->getImage())) {
                  $row[] = $e->getImage()->getName();
              } else {
                  $row[] = "";
              }
        
              if (is_object($e->getImage())) {
                  $UrlPicture = $this->container->get('pi_app_admin.twig.extension.route')->getMediaUrlFunction($e->getImage(), 'reference', true, $e->getUpdatedAt(), 'gedmo_Test');
                  $row[] = '<a href="#" title=\'<img width="450px" src="'.$UrlPicture.'">\' class="info-tooltip"><img width="20px" src="'.$UrlPicture.'"></a>';
                  
                  if (is_object($e->getUpdatedAt())) {
                      $row[] = $e->getUpdatedAt()->format('d-m-Y');
                  } else {
                      $row[] = "";
                  }
              }
              
              // create enabled/disabled buttons
              $Urlenabled     = $this->container->get('templating.helper.assets')->getUrl("bundles/piappadmin/images/grid/button-green.png");
              $Urldisabled     = $this->container->get('templating.helper.assets')->getUrl("bundles/piappadmin/images/grid/button-red.png");
              if ($e->getEnabled()) {
                  $row[] = '<img width="17px" src="'.$Urlenabled.'">';
              } else {
                  $row[] = '<img width="17px" src="'.$Urldisabled.'">';
              }
              // create action links
              $route_path_show = $this->container->get('pi_app_admin.twig.extension.route')->getUrlByRouteFunction('admin_gedmo_media_show', array('id'=>$e->getId(), 'NoLayout'=>$NoLayout, 'category'=>$category));
              $route_path_edit = $this->container->get('pi_app_admin.twig.extension.route')->getUrlByRouteFunction('admin_gedmo_media_edit', array('id'=>$e->getId(), 'NoLayout'=>$NoLayout, 'category'=>$category, 'status'=>$e->getStatus()));
              $actions = '<a href="'.$route_path_show.'" title="'.$this->container->get('translator')->trans('pi.grid.action.show').'" class="icon-3 info-tooltip" >&nbsp;</a>'; //actions
              $actions = '<a href="'.$route_path_edit.'" title="'.$this->container->get('translator')->trans('pi.grid.action.edit').'" class="icon-1 info-tooltip" >&nbsp;</a>'; //actions
              $row[] = $actions;
              
              $output['aaData'][] = $row ;
            }
            $response = new Response(json_encode( $output ));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        }
        
        if (!$is_Server_side) {
           $entities    = $em->getRepository("PluginsContentBundle:Test")->findTranslationsByQuery($locale, $query->getQuery(), 'object', false);
        } else {
           $entities   = null;
        }    	

        return $this->render("PluginsContentBundle:Test:$template", array(
            'isServerSide' => $is_Server_side,
            'entities'	=> $entities,
            'NoLayout'	=> $NoLayout,
            'category'	=> $category,
        ));
    }

    /**
     * Finds and displays a Test entity.
     *
     * @Secure(roles="IS_AUTHENTICATED_ANONYMOUSLY")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function showAction($id)
    {
        $em         = $this->getDoctrine()->getManager();
        $locale        = $this->container->get('request')->getLocale();
        $entity     = $em->getRepository("PluginsContentBundle:Test")->findOneByEntity($locale, $id, 'object');
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "show.html.twig"; else $template = "show.html.twig";        

        if (!$entity) {
            throw ControllerException::NotFoundException('Test');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:Test:$template", array(
            'entity'      => $entity,
            'NoLayout'      => $NoLayout,
            'category'      => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new Test entity.
     *
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function newAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $category   = $this->container->get('request')->query->get('category', '');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new.html.twig";
        
        $entity     = new Test();
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $blocgeneral = new Blocgeneral();
        $blocgeneral->setCreatedAt(new \DateTime());
        $blocgeneral->setUpdatedAt(new \Datetime());
        $entity->setBlocgeneral($blocgeneral);
        
        $form       = $this->createForm(new TestType($em, $this->container), $entity, array('show_legend' => false));
            
        return $this->render("PluginsContentBundle:Test:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Creates a new Test entity.
     *
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>     
     */
    public function createAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $locale        = $this->container->get('request')->getLocale();
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new.html.twig";        
    
        $entity     = new Test();
        $request     = $this->getRequest();
        $form        = $this->createForm(new TestType($em, $this->container), $entity, array('show_legend' => false));
        $form->bind($request);

        if(!$form->get('blocgeneral')->get('published_at')->getData()) {
            $form->get('blocgeneral')->get('published_at')->addError(new FormError("Cette valeur ne doit pas être vide"));
        }
        if(!$form->get('blocgeneral')->get('archive_at')->getData()) {
            $form->get('blocgeneral')->get('archive_at')->addError(new FormError("Cette valeur ne doit pas être vide"));
        }

        if ($form->isValid()) {
        		$entity->setTranslatableLocale($locale);
        		$em->persist($entity);
        		$em->flush();

                //return $this->redirect($this->generateUrl('admin_content_test_show', array('id' => $entity->getId(), 'NoLayout' => $NoLayout, 'category' => $category)));
                return $this->redirect($this->generateUrl('admin_content_test_edit', array('id' => $entity->getId(), 'NoLayout' => $NoLayout, 'category' => $category)));                
        }

        return $this->render("PluginsContentBundle:Test:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Displays a form to edit an existing Test entity.
     *
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function editAction($id)
    {
        $em         = $this->getDoctrine()->getManager();
        $locale        = $this->container->get('request')->getLocale();
        
        if (!empty($id)){
            $entity    = $em->getRepository("PluginsContentBundle:Test")->findOneByEntity($locale, $id, 'object');
        } else {
            $slug    = $this->container->get('bootstrap.RouteTranslator.factory')->getMatchParamOfRoute('slug', $locale, true);
            $entity    = $this->container->get('doctrine')->getManager()->getRepository("PluginsContentBundle:Test")->getEntityByField($locale, array('content_search' => array('slug' =>$slug)), 'object');
        }        
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:Test")->find($id);
            $entity->addTranslation(new TestTranslation($locale));            
        }

        $entity->getBlocgeneral()->setUpdatedAt(new \Datetime());
        $editForm   = $this->createForm(new TestType($em, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:Test:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Edits an existing Test entity.
     *
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>   
     */
    public function updateAction($id)
    {
        $em         = $this->getDoctrine()->getManager();
        $locale        = $this->container->get('request')->getLocale();
        $entity     = $em->getRepository("PluginsContentBundle:Test")->findOneByEntity($locale, $id, "object"); 
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:Test")->find($id);
        }
        if ($this->container->get('security.context')->isGranted("ROLE_USER")) {
        	$originalQuestions = array();
        	foreach ($entity->getQuestions() as $Question) {
        		$originalQuestions[] = $Question;
        	}
        }
        $editForm   = $this->createForm(new TestType($em, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($this->getRequest(), $entity);

        if(!$editForm->get('blocgeneral')->get('published_at')->getData()) {
            $editForm->get('blocgeneral')->get('published_at')->addError(new FormError("Cette valeur ne doit pas être vide"));
        }
        if(!$editForm->get('blocgeneral')->get('archive_at')->getData()) {
            $editForm->get('blocgeneral')->get('archive_at')->addError(new FormError("Cette valeur ne doit pas être vide"));
        }

        if ($editForm->isValid()) {
                if ($this->container->get('security.context')->isGranted("ROLE_USER")) {
                    foreach ($entity->getQuestions() as $Question) {
                        foreach ($originalQuestions as $key => $toDel) {
                            if ($toDel->getId() === $Question->getId()) {
                                unset($originalQuestions[$key]);
                            }
                        }
                    }
                    foreach ($originalQuestions as $Question) {
                        $entity->removeQuestions($Question);
                        $em->remove($Question);
                    }
                }            
                $entity->setTranslatableLocale($locale);
                $em->persist($entity);
                $em->flush();                

	            return $this->redirect($this->generateUrl('admin_content_test_edit', array('id' => $id, 'NoLayout' => $NoLayout, 'category' => $category)));
        } else {
        	$this->setFlashErrorMessages($editForm);
//             $errors = $this->getErrorMessages($form);
//             $contact = isset($errors["contact"]) ? $errors["contact"] : null;
//             if($contact) {$errors["Informations générales"] = $errors["contact"]; unset($errors["contact"]); }
//             $user = isset($errors["user"]) ? $errors["user"] : null;
//             if($user) {$errors["Profil système"] = $errors["user"]; unset($errors["user"]); }
//             $errors = \PiApp\AdminBundle\Util\PiArrayManager::convertArrayToString($errors, $this->get('translator'), 'pi.form.label.field.', '', "<br />");
//             $this->setFlashMessages($errors);
        }

        return $this->render("PluginsContentBundle:Test:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Deletes a Test entity.
     *
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *     
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>     
     */
    public function deleteAction($id)
    {
        $em          = $this->getDoctrine()->getManager();
        $locale         = $this->container->get('request')->getLocale();
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');        
        $category   = $this->container->get('request')->query->get('category');
    
        $form          = $this->createDeleteForm($id);
        $request     = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {            
            $entity = $em->getRepository("PluginsContentBundle:Test")->findOneByEntity($locale, $id, 'object');

            if (!$entity) {
                throw ControllerException::NotFoundException('Test');
            }

            try {
                    if (method_exists($entity, 'setArchived')) {
                        $entity->setArchived(true);
                    }
                    if (method_exists($entity, 'setEnabled')) {
                        $entity->setEnabled(false);
                    }
                    if (method_exists($entity, 'setArchiveAt')) {
                        $entity->setArchiveAt(new \DateTime());
                    }
                    if (method_exists($entity, 'setPosition')) {
                        $entity->setPosition(null);
                    }

                    $entity->getBlocgeneral()->setArchived(true);
                    $entity->getBlocgeneral()->setEnabled(false);
                    $entity->getBlocgeneral()->setArchiveAt(new \DateTime());

                    //$em->remove($entity);
                    $em->persist($entity);
                    $em->flush();                    
            } catch (\Exception $e) {
                $this->container->get('request')->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
            }
        }
        
        return $this->redirect($this->generateUrl('admin_content_bloc_general', array('NoLayout' => $NoLayout, 'category' => $category, 'type' => 'test')));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Template : Finds and displays a Test entity.
     * 
     * @Cache(maxage="86400")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com> 
     */
    public function _template_showAction($id, $template = '_tmp_show.html.twig', $lang = "")
    {
        $em     = $this->getDoctrine()->getManager();
        
        if (empty($lang))
            $lang    = $this->container->get('request')->getLocale();
            
        $entity = $em->getRepository("PluginsContentBundle:Test")->findOneByEntity($lang, $id, 'object', false);
        
        if (!$entity) {
            throw ControllerException::NotFoundException('Test');
        }
        
        if (method_exists($entity, "getTemplate") && $entity->getTemplate() != "")
            $template = $entity->getTemplate();         
    
        return $this->render("PluginsContentBundle:Test:$template", array(
                'entity'    => $entity,
                'locale'    => $lang,
        ));
    }

    /**
     * Template : Finds and displays a list of Test entity.
     * 
     * @Cache(maxage="86400")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com> 
     */
    public function _template_listAction($category = '', $MaxResults = null, $template = '_tmp_list.html.twig', $order = 'DESC', $lang = "")
    {
        $em         = $this->getDoctrine()->getManager();

        if (empty($lang))
            $lang    = $this->container->get('request')->getLocale();
            
        $query        = $em->getRepository("PluginsContentBundle:Test")->getAllByCategory($category, $MaxResults, $order)->getQuery();
        $entities   = $em->getRepository("PluginsContentBundle:Test")->findTranslationsByQuery($lang, $query, 'object', false);                   

        return $this->render("PluginsContentBundle:Test:$template", array(
            'entities' => $entities,
            'cat'       => ucfirst($category),
            'locale'   => $lang,
        ));
    } 

    /**
     * Template : Finds and displays an archive of Test entity.
     * 
     * @Cache(maxage="86400")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com> 
     */
    public function _template_archiveAction($MaxResults = null, $template = '_tmp_archive.html.twig', $order = 'DESC', $lang = "")
    {
        $em         = $this->getDoctrine()->getManager();
    
        if (empty($lang))
            $lang    = $this->container->get('request')->getLocale();
         
        if (isset($_GET['page']) && !empty($_GET['page']))
            $page     = $_GET['page'];
        else
            $page     = 1;
         
        $paginator             = $this->container->get('knp_paginator');
    
        $count                 = $em->getRepository("PluginsContentBundle:Test")->count(1);
        $query_pagination    = $em->getRepository("PluginsContentBundle:Test")->getAllByCategory('', null, $order)->getQuery();
        $query_pagination->setHint('knp_paginator.count', $count);
         
        $pagination = $paginator->paginate(
                $query_pagination,
                $page,    /*page number*/
                $MaxResults        /*limit per page*/
        );
         
        //print_r($pagination);exit;
         
        $query_pagination->setFirstResult(($page-1)*$MaxResults);
        $query_pagination->setMaxResults($MaxResults);
        $query_pagination    = $em->getRepository("PluginsContentBundle:Test")->setTranslatableHints($query_pagination, $lang, false);
        $entities            = $em->getRepository("PluginsContentBundle:Test")->findTranslationsByQuery($lang, $query_pagination, 'object', false);
         
        return $this->render("PluginsContentBundle:Test:$template", array(
                'entities'        => $entities,
                'pagination'    => $pagination,
                'locale'        => $lang,
                'lang'            => $lang,
        ));        

    }

    public function show_images_cropAction($id){

        $em         = $this->getDoctrine()->getManager();
        $locale        = $this->container->get('request')->getLocale();
        $entity     = $em->getRepository("PluginsContentBundle:Test")->findOneByEntity($locale, $id, "object");

        $template = "show_images_crop.html.twig";

        return $this->render("PluginsContentBundle:Test:$template", array(
            'entity' => $entity,
        ));
    }


    
    /**
     * Template : Finds and displays a list of Article entity.
     * 
     * @Cache(maxage="86400")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com> 
     */
    public function get_contentAction($id = '', $template = '_tmp_list.html.twig', $lang = "")
    {
        $em         = $this->getDoctrine()->getManager();
 
        if (empty($lang))
            $lang    = $this->container->get('request')->getLocale();
        if(empty($id)){
            $slug = $this->container->get('bootstrap.RouteTranslator.factory')->getMatchParamOfRoute('slug', $lang);
			if(empty($slug)){
				$slug	= $this->container->get('bootstrap.RouteTranslator.factory')->getMatchParamOfRoute('slug', $lang, true);
			}              
            $entity = $em->getRepository("PluginsContentBundle:BlocGeneral")->getEntityByField($lang, array('content_search' => array('slug' =>$slug)), 'object');
        }else{
            $entity = $em->getRepository("PluginsContentBundle:BlocGeneral")->findOneByEntity($lang, $id, 'object', false);
        }
        $test = $entity->getTest();

        return $this->render("PluginsContentBundle:Test:$template", array(
            'entity' => $entity,
            'test' => $test,
            'locale'   => $lang,

        ));
    } 

    
}