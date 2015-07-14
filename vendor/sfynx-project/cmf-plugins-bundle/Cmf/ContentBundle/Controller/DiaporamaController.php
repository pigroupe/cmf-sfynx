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
namespace Cmf\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sfynx\CoreBundle\Controller\abstractController;
use Sfynx\ToolBundle\Exception\ControllerException;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Cmf\ContentBundle\Entity\Diaporama;
use Cmf\ContentBundle\Entity\BlocGeneral;
use Cmf\ContentBundle\Form\DiaporamaType;
use Cmf\ContentBundle\Entity\Translation\DiaporamaTranslation;

use Symfony\Component\Form\FormError;

/**
 * Diaporama controller.
 *
 *
 * @category   PI_CRUD_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class DiaporamaController extends abstractController
{
    protected $_entityName = "PluginsContentBundle:Diaporama";

    /**
     * Enabled Diaporama entities.
     *
     * @Route("/admin/gedmo/diaporama/enabled", name="admin_gedmo_diaporama_enabledentity_ajax")
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
     * Disable Diaporama entities.
     * 
     * @Route("/admin/gedmo/diaporama/disable", name="admin_gedmo_diaporama_disablentity_ajax")
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
     * Change the position of a Diaporama entity.
     *
     * @Route("/admin/gedmo/diaporama/position", name="admin_gedmo_diaporama_position_ajax")
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
     * Delete a Diaporama entity.
     *
     * @Route("/admin/gedmo/diaporama/delete", name="admin_gedmo_diaporama_deletentity_ajax")
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
     * archive Diaporama entities.
     *
     * @Route("/admin/gedmo/diaporama/archive", name="admin_gedmo_diaporama_archiventity_ajax")
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
     * Lists all Diaporama entities.
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
    		$query    = $em->getRepository("PluginsContentBundle:Diaporama")->setContainer($this->container)->getAllByCategory($category, null, '', 'DESC', false, true, true);
    	} else {
    		$query    = $em->getRepository("PluginsContentBundle:Diaporama")->getAllByCategory($category, null, '', 'ASC', false, true, true);
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
                  $UrlPicture = $this->container->get('pi_app_admin.twig.extension.route')->getMediaUrlFunction($e->getImage(), 'reference', true, $e->getUpdatedAt(), 'gedmo_Diaporama');
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
           $entities    = $em->getRepository("PluginsContentBundle:Diaporama")->findTranslationsByQuery($locale, $query->getQuery(), 'object', false);
        } else {
           $entities   = null;
        }    	

        return $this->render("PluginsContentBundle:Diaporama:$template", array(
            'isServerSide' => $is_Server_side,
            'entities'	=> $entities,
            'NoLayout'	=> $NoLayout,
            'category'	=> $category,
        ));
    }

    /**
     * Finds and displays a Diaporama entity.
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
        $entity     = $em->getRepository("PluginsContentBundle:Diaporama")->findOneByEntity($locale, $id, 'object');
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "show.html.twig"; else $template = "show.html.twig";        

        if (!$entity) {
            throw ControllerException::NotFoundException('Diaporama');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:Diaporama:$template", array(
            'entity'      => $entity,
            'NoLayout'      => $NoLayout,
            'category'      => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new Diaporama entity.
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
        
        $entity     = new Diaporama();
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $blocgeneral = new Blocgeneral();
        $blocgeneral->setCreatedAt(new \DateTime());
        $blocgeneral->setUpdatedAt(new \Datetime());
        $entity->setBlocgeneral($blocgeneral);
        
        $form       = $this->createForm(new DiaporamaType($em, $this->container), $entity, array('show_legend' => false));
            
        return $this->render("PluginsContentBundle:Diaporama:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Creates a new Diaporama entity.
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
    
        $entity     = new Diaporama();
        $request     = $this->getRequest();
        $form        = $this->createForm(new DiaporamaType($em, $this->container), $entity, array('show_legend' => false));
		$data 	= $this->getRequest()->get($form->getName(), array());
		$listmed = array();
		$duplicate = 0;
        if(isset($data['medias'])){
            foreach($data['medias'] as $med){
                if(in_array($med['media'], $listmed)){
                    $duplicate = 1;
                }else{
                    array_push($listmed, $med['media']);
                }
            }
        }

		if(in_array($data['blocgeneral']['media'], $listmed)){
				$duplicate = 1;
		}
        if($duplicate == 0){ 
            
            $form->bind($request);

            if(!$form->get('blocgeneral')->get('published_at')->getData()) {
                $form->get('blocgeneral')->get('published_at')->addError(new FormError("Cette valeur ne doit pas être vide"));
            }
            if(!$form->get('blocgeneral')->get('archive_at')->getData()) {
                $form->get('blocgeneral')->get('archive_at')->addError(new FormError("Cette valeur ne doit pas être vide"));
            }

            if ($form->isValid()) {
                    $entity->setTranslatableLocale($locale);
                    // We persist all medias diapo
                    foreach($entity->getMedias() as $media_diaporama) {
                    	$entity->addMedias($media_diaporama);
                    }
                    $em->persist($entity);
                    $em->flush();

                    //return $this->redirect($this->generateUrl('admin_content_diaporama_show', array('id' => $entity->getId(), 'NoLayout' => $NoLayout, 'category' => $category)));
                    return $this->redirect($this->generateUrl('admin_content_diaporama_edit', array('id' => $entity->getId(), 'NoLayout' => $NoLayout, 'category' => $category)));                
            }
            
        } else {
            $this->setFlashMessages('vous devez choisir des images différentes pour le diaporama');
        }        

        return $this->render("PluginsContentBundle:Diaporama:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Displays a form to edit an existing Diaporama entity.
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
            $entity    = $em->getRepository("PluginsContentBundle:Diaporama")->findOneByEntity($locale, $id, 'object');
        } else {
            $slug    = $this->container->get('bootstrap.RouteTranslator.factory')->getMatchParamOfRoute('slug', $locale, true);
            $entity    = $this->container->get('doctrine')->getManager()->getRepository("PluginsContentBundle:Diaporama")->getEntityByField($locale, array('content_search' => array('slug' =>$slug)), 'object');
        }        
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:Diaporama")->find($id);
            $entity->addTranslation(new DiaporamaTranslation($locale));            
        }

        $entity->getBlocgeneral()->setUpdatedAt(new \Datetime());
        $editForm   = $this->createForm(new DiaporamaType($em, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:Diaporama:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Edits an existing Diaporama entity.
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
        $entity     = $em->getRepository("PluginsContentBundle:Diaporama")->findOneByEntity($locale, $id, "object"); 
        //
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        
		//
        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:Diaporama")->find($id);
        }
        if ($this->container->get('security.context')->isGranted("ROLE_USER")) {
        	$originalMedias = array();
        	foreach ($entity->getMedias() as $media) {
        		$originalMedias[] = $media;
        	}
        }    
        
        //print_r($this->getRequest());exit;
        $editForm   = $this->createForm(new DiaporamaType($em, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);
        
		$data 	= $this->getRequest()->get($editForm->getName(), array());
		$listmed = array();
		$duplicate = 0;


        if(isset($data['medias'])){
            foreach($data['medias'] as $med){
                if(in_array($med['media'], $listmed)){
                    $duplicate = 1;
                }else{
                    array_push($listmed, $med['media']);
                }
            }
        } else {
            $editForm->get('blocgeneral')->addError(new FormError('Vous devez ajouter une image en cliquant sur le bouton "Ajouter une image" pour valider le formulaire'));
        }

        if(isset($data['blocgeneral']['media']) && in_array($data['blocgeneral']['media'], $listmed)){
				$duplicate = 1;
		}
        if($duplicate == 0){        
            $editForm->bind($this->getRequest(), $entity);

            // we add error validators.
            if(!$editForm->get('blocgeneral')->get('published_at')->getData()) {
                $editForm->get('blocgeneral')->get('published_at')->addError(new FormError("Cette valeur ne doit pas être vide"));
            }
            if(!$editForm->get('blocgeneral')->get('archive_at')->getData()) {
                $editForm->get('blocgeneral')->get('archive_at')->addError(new FormError("Cette valeur ne doit pas être vide"));
            }        
            // 

            if ($editForm->isValid()) {
                    if ($this->container->get('security.context')->isGranted("ROLE_USER")) { 
                        foreach ($entity->getMedias() as $media) {
                            foreach ($originalMedias as $key => $toDel) {
                                if ($toDel->getId() === $media->getId()) {
                                    unset($originalMedias[$key]);
                                }
                            }
                        }
                        foreach ($originalMedias as $media) {
                            $entity->removeMedias($media);
                            $em->remove($media);
                        }                    
                    }
                    $entity->setTranslatableLocale($locale);
                    // We persist all medias diapo
                    foreach($entity->getMedias() as $media_diaporama) {
                    	$entity->addMedias($media_diaporama);
                    }
                    $em->persist($entity);
                    $em->flush();

	                return $this->redirect($this->generateUrl('admin_content_diaporama_edit', array('id' => $id, 'NoLayout' => $NoLayout, 'category' => $category)));
            } else {
                $this->setFlashErrorMessages($editForm);
            }            
        } else {
            $this->setFlashMessages('vous devez choisir des images différentes pour le diaporama');
        }

        return $this->render("PluginsContentBundle:Diaporama:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Deletes a Diaporama entity.
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
            $entity = $em->getRepository("PluginsContentBundle:Diaporama")->findOneByEntity($locale, $id, 'object');
            if (!$entity) {
                throw ControllerException::NotFoundException('Diaporama');
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
            		            		
            		$em->persist($entity);
            		$em->flush();
            } catch (\Exception $e) {
                $this->container->get('request')->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
            }
        }

        return $this->redirect($this->generateUrl('admin_content_bloc_general', array('NoLayout' => $NoLayout, 'category' => $category, 'type' => 'diaporama')));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

}