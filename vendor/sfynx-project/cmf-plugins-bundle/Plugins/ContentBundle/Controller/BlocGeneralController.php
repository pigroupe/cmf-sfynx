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

use Plugins\ContentBundle\Entity\BlocGeneral;
use Plugins\ContentBundle\Form\BlocGeneralType;
use Plugins\ContentBundle\Entity\Translation\BlocGeneralTranslation;

/**
 * BlocGeneral controller.
 *
 *
 * @category   PI_CRUD_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class BlocGeneralController extends abstractController
{
    protected $_entityName = "PluginsContentBundle:BlocGeneral";

    /**
     * Enabled BlocGeneral entities.
     *
     * @Route("/admin/gedmo/blocgeneral/enabled", name="admin_gedmo_blocgeneral_enabledentity_ajax")
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *     
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function enabledajaxAction()
    {
        $request = $this->container->get('request');
		$em		 = $this->getDoctrine()->getManager();
		 
		if($request->isXmlHttpRequest()){
			$data		= $request->get('data', null);
			$new_data	= null;
				
			foreach ($data as $key => $value) {
				$values 	= explode('_', $value);
				$id	    	= $values[2];
				$type		= $values[1];
				$position	= $values[0];
	
				$new_data[$key] = array('position'=>$position, 'id'=>$id, 'type'=>$type);
				$new_pos[$key]  = $position;
			}
			array_multisort($new_pos, SORT_ASC, $new_data);
	
			krsort($new_data);
			foreach ($new_data as $key => $value) {
                $access = 1;
				if($value['type'] == "article"){
					$entity = $em->getRepository("PluginsContentBundle:Article")->find($value['id']);
				}elseif($value['type'] == "dossier"){
					$entity = $em->getRepository("PluginsContentBundle:Diaporama")->find($value['id']);
				}elseif($value['type'] == "page"){
					$entity = $em->getRepository("PluginsContentBundle:Page")->find($value['id']);
				}elseif($value['type'] == "test"){
					$entity = $em->getRepository("PluginsContentBundle:Test")->find($value['id']);
				}				
                $entity->setArchived(false);
                $entity->setEnabled(true);
                $entity->setArchiveAt(null);
                $entity->getBlocgeneral()->setArchived(false);
                $entity->getBlocgeneral()->setEnabled(true);
                $entity->getBlocgeneral()->setArchiveAt(null);

                if (method_exists($entity, 'setPosition')) {
                    $entity->setPosition(null);
                }

                $em->persist($entity);
                $em->flush();                    
			}
			$em->clear();	
			// we disable all flash message
			$this->container->get('session')->clearFlashes();
	
			$tab= array();
			$tab['id'] = '-1';
			$tab['error'] = '';
			$tab['fieldErrors'] = '';
			$tab['data'] = '';
			 
			$response = new Response(json_encode($tab));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}else
			throw ControllerException::callAjaxOnlySupported('enabledajax');
    }

    /**
     * Disable BlocGeneral entities.
     * 
     * @Route("/admin/gedmo/blocgeneral/disable", name="admin_gedmo_blocgeneral_disablentity_ajax")
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *     
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function disableajaxAction()
    {
        $request = $this->container->get('request');
		$em		 = $this->getDoctrine()->getManager();
		 
		if($request->isXmlHttpRequest()){
			$data		= $request->get('data', null);
			$new_data	= null;
				
			foreach ($data as $key => $value) {
				$values 	= explode('_', $value);
				$id	    	= $values[2];
				$type		= $values[1];
				$position	= $values[0];
	
				$new_data[$key] = array('position'=>$position, 'id'=>$id, 'type'=>$type);
				$new_pos[$key]  = $position;
			}
			array_multisort($new_pos, SORT_ASC, $new_data);
			krsort($new_data);
			foreach ($new_data as $key => $value) {
					if($value['type'] == "article"){
						$entity = $em->getRepository("PluginsContentBundle:Article")->find($value['id']);
					}elseif($value['type'] == "dossier"){
						$entity = $em->getRepository("PluginsContentBundle:Diaporama")->find($value['id']);
					}elseif($value['type'] == "page"){
						$entity = $em->getRepository("PluginsContentBundle:Page")->find($value['id']);
					}elseif($value['type'] == "test"){
						$entity = $em->getRepository("PluginsContentBundle:Test")->find($value['id']);
					}					
                    $entity->setEnabled(false);
                    $entity->getBlocgeneral()->setEnabled(false);

                    if (method_exists($entity, 'setPosition')) {
                        $entity->setPosition(null);
                    }

                    $em->persist($entity);
                    $em->flush();                    
			}
			$em->clear();
			
			// we disable all flash message
			$this->container->get('session')->clearFlashes();
	
			$tab= array();
			$tab['id'] = '-1';
			$tab['error'] = '';
			$tab['fieldErrors'] = '';
			$tab['data'] = '';
			
			$response = new Response(json_encode($tab));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		} else
			throw ControllerException::callAjaxOnlySupported('enabledajax');
    } 

    /**
     * Change the position of a BlocGeneral entity.
     *
     * @Route("/admin/gedmo/blocgeneral/position", name="admin_gedmo_blocgeneral_position_ajax")
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
     * Delete a BlocGeneral entity.
     *
     * @Route("/admin/gedmo/blocgeneral/delete", name="admin_gedmo_blocgeneral_deletentity_ajax")
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *     
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteajaxAction()
    {
        $request = $this->container->get('request');
		$em		 = $this->getDoctrine()->getManager();
		 
		if($request->isXmlHttpRequest()){
			$data		= $request->get('data', null);
			$new_data	= null;
				
			foreach ($data as $key => $value) {
				$values 	= explode('_', $value);
				$id	    	= $values[2];
				$type		= $values[1];
				$position	= $values[0];
	
				$new_data[$key] = array('position'=>$position, 'id'=>$id, 'type'=>$type);
				$new_pos[$key]  = $position;
			}
			array_multisort($new_pos, SORT_ASC, $new_data);	
			krsort($new_data);
			foreach ($new_data as $key => $value) {
					if($value['type'] == "article"){
						$entity = $em->getRepository("PluginsContentBundle:Article")->find($value['id']);
					}elseif($value['type'] == "dossier"){
						$entity = $em->getRepository("PluginsContentBundle:Diaporama")->find($value['id']);
					}elseif($value['type'] == "page"){
						$entity = $em->getRepository("PluginsContentBundle:Page")->find($value['id']);
					}elseif($value['type'] == "test"){
						$entity = $em->getRepository("PluginsContentBundle:Test")->find($value['id']);
					}					
                    $em->remove($entity);
                    $em->flush();                    
			}
			$em->clear();
	
			// we disable all flash message
			$this->container->get('session')->clearFlashes();
	
			$tab= array();
			$tab['id'] = '-1';
			$tab['error'] = '';
			$tab['fieldErrors'] = '';
			$tab['data'] = '';
			 
			$response = new Response(json_encode($tab));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}else
			throw ControllerException::callAjaxOnlySupported('enabledajax');
    }   

    /**
     * archive BlocGeneral entities.
     *
     * @Route("/admin/gedmo/blocgeneral/archive", name="admin_gedmo_blocgeneral_archiventity_ajax")
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *     
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function archiveajaxAction()
    {
        $request = $this->container->get('request');
		$em		 = $this->getDoctrine()->getManager();
		 
		if($request->isXmlHttpRequest()){
			$data		= $request->get('data', null);
			$new_data	= null;
				
			foreach ($data as $key => $value) {
				$values 	= explode('_', $value);
				$id	    	= $values[2];
				$type		= $values[1];
				$position	= $values[0];
	
				$new_data[$key] = array('position'=>$position, 'id'=>$id, 'type'=>$type);
				$new_pos[$key]  = $position;
			}
			array_multisort($new_pos, SORT_ASC, $new_data);	
			krsort($new_data);
			foreach ($new_data as $key => $value) {
					if($value['type'] == "article"){
						$entity = $em->getRepository("PluginsContentBundle:Article")->find($value['id']);
					}elseif($value['type'] == "dossier"){
						$entity = $em->getRepository("PluginsContentBundle:Diaporama")->find($value['id']);
					}elseif($value['type'] == "page"){
						$entity = $em->getRepository("PluginsContentBundle:Page")->find($value['id']);
					}elseif($value['type'] == "test"){
						$entity = $em->getRepository("PluginsContentBundle:Test")->find($value['id']);
					}					
                    $entity->setArchived(true);
                    $entity->setEnabled(false);
                    $entity->setArchiveAt(new \DateTime());

                    $entity->getBlocgeneral()->setArchived(true);
                    $entity->getBlocgeneral()->setEnabled(false);
                    $entity->getBlocgeneral()->setArchiveAt(new \DateTime());

                    if (method_exists($entity, 'setPosition')) {
                        $entity->setPosition(null);
                    }

                    $em->persist($entity);
                    $em->flush();                    
			}
			$em->clear();
	
			// we disable all flash message
			$this->container->get('session')->clearFlashes();
	
			$tab= array();
			$tab['id'] = '-1';
			$tab['error'] = '';
			$tab['fieldErrors'] = '';
			$tab['data'] = '';
			 
			$response = new Response(json_encode($tab));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}else
			throw ControllerException::callAjaxOnlySupported('enabledajax');
    }
    
    /**
     * Lists all BlocGeneral entities.
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
        $type_entity = $this->container->get('request')->query->get('type');
        if (!$NoLayout) 	$template = "index.html.twig"; else $template = "index.html.twig";
        
        if ($NoLayout){
    		$query    = $em->getRepository("PluginsContentBundle:BlocGeneral")->setContainer($this->container)->getAllByCategory($category, null, '', '', false, true, true);
    	} else {
    		$query    = $em->getRepository("PluginsContentBundle:BlocGeneral")->getAllByCategory($category, null, '', '', false, true, true);
    	}
    	$query
    	->leftJoin('a.subrub', 'subr')
    	->leftJoin('a.tag', 'tag')
    	->leftJoin('tag.translations', 'tagtrans')
    	->leftJoin('a.diaporama', 'd')
    	->leftJoin('a.test', 't')
    	->leftJoin('a.article', 'c')
    	->leftJoin('a.page', 'p');
    	//
    	if (!empty($type_entity)) {
    		// we select only contents which are asked.
    		if ( $type_entity == 'diaporama' ) {
    			$query->andWhere($query->expr()->isNotNull('d.id'));
    		} else if ( $type_entity == 'test' ) {
    			$query->andWhere($query->expr()->isNotNull('t.id'));
    		} else if ( $type_entity == 'article' ) {
    			$query->andWhere($query->expr()->isNotNull('c.id'));
    		} else if ( $type_entity == 'page' ) {
    			$query->andWhere($query->expr()->isNotNull('p.id'));
    		}
    	}
        $is_Server_side = true;
        //
        if ($request->isXmlHttpRequest() && $is_Server_side) {
           $aColumns    = array(
           		'a.id',
           		'subr.id',
           		'tagtrans.content',
           		'a.title',
           		"case when d.id IS NOT NULL then 'diaporama' when t.id IS NOT NULL then 'test' when c.id IS NOT NULL then 'article' else 'page' end",
           		"a.author",
           		"case when a.enabled = 1 then 'tif' when a.archive_at IS NOT NULL and a.archived = 1  then 'Archive' else 'En attente dactivation' end",
           		'a.created_at',
           		'a.published_at',
           		'a.archive_at',
           		"a.enabled"
           );
           $q1 = clone $query;
           $q2 = clone $query;
           $result    = $this->createAjaxQuery('select',$aColumns, $q1, 'a', null, array(
           		0 =>array('column'=>'a.created_at', 'format'=>'Y-m-d', 'idMin'=>'minc', 'idMax'=>'maxc'),
           		1 =>array('column'=>'a.published_at', 'format'=>'Y-m-d', 'idMin'=>'minp', 'idMax'=>'maxp'),
           		2 =>array('column'=>'a.archive_at', 'format'=>'Y-m-d', 'idMin'=>'mina', 'idMax'=>'maxa')
           ));
           $total    = $this->createAjaxQuery('count',$aColumns, $q2, 'a', null, array(
           		0 =>array('column'=>'a.created_at', 'format'=>'Y-m-d', 'idMin'=>'minc', 'idMax'=>'maxc'),
           		1 =>array('column'=>'a.published_at', 'format'=>'Y-m-d', 'idMin'=>'minp', 'idMax'=>'minp'),
           		2 =>array('column'=>'a.archive_at', 'format'=>'Y-m-d', 'idMin'=>'mina', 'idMax'=>'maxa')
           ));
        
           $output = array(
               "sEcho" => intval($request->get('sEcho')),
                "iTotalRecords" => $total,
                "iTotalDisplayRecords" => $total,
                "aaData" => array()
           );
        
           foreach ($result as $e) {
              // we set the resulmt to the $lang language value
           	  $e->setTranslatableLocale($locale);
           	  $em->refresh($e);
	          if ($e->getArticle() instanceof \Plugins\ContentBundle\Entity\Article) {
	           		$type		= "article";
	           		$idEntity	= $e->getArticle()->getId();
                    $url_edit	= $this->container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_content_article_edit", array("id"=>$idEntity, "NoLayout"=>$NoLayout, "category"=>$category));
                    $url_crop	= $this->container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_content_article_show_images_crop", array("id"=>$idEntity));                        
	          } elseif (($e->getDiaporama() instanceof \Plugins\ContentBundle\Entity\Diaporama)){
	           		$type		= "dossier";
	           		$idEntity	= $e->getDiaporama()->getId();
                    $url_edit	= $this->container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_content_diaporama_edit", array("id"=>$idEntity, "NoLayout"=>$NoLayout, "category"=>$category));
          			$url_crop	= $this->container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_content_diaporama_show_images_crop", array("id"=>$idEntity));
	          } elseif (($e->getTest() instanceof \Plugins\ContentBundle\Entity\Test)){
	           		$type		= "test";
	           		$idEntity	= $e->getTest()->getId();
                    $url_edit	= $this->container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_content_test_edit", array("id"=>$idEntity, "NoLayout"=>$NoLayout, "category"=>$category));
          			$url_crop	= $this->container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_content_test_show_images_crop", array("id"=>$idEntity));
	          } elseif (($e->getPage() instanceof \Plugins\ContentBundle\Entity\Page)){
	           		$type		= "page";
	           		$idEntity	= $e->getPage()->getId();
                    $url_edit	= $this->container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_content_page_edit", array("id"=>$idEntity, "NoLayout"=>$NoLayout, "category"=>$category));
          			$url_crop	= $this->container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_content_page_show_images_crop", array("id"=>$idEntity));
	          }
	          
              $row = array();
              $row[] = (string) $e->getId() . '_' . $type . '_' . $idEntity;
              $row[] = (string) $e->getId();
              
              if ($e->getSubrub() instanceof \Plugins\ContentBundle\Entity\Rub) {
              	  $e->getSubrub()->setTranslatableLocale($locale);
              	  $em->refresh($e->getSubrub());
                  $row[] = (string) $e->getSubrub()->getTitle();
              } else {
                  $row[] = "";
              }
              
              if ($e->getTag()->count() >= 1) {
              	$tmp="";
              	foreach($e->getTag() as $tag) {
              		$tag->setTranslatableLocale($locale);
              		$em->refresh($tag);              		
              		$tmp .= $tag->getTitle() . '  ';
              	}
              	$row[] = (string) $tmp;
              } else {
              	$row[] = "";
              }
              
              $row[] = (string) '<a target="_blank" href="'.$url_edit.'" class="info-tooltip">'.$e->getTitle().'</a>';
              $row[] = (string) $this->container->get('plugins.twig.extension.content.tool')->typeContentFilter($e);

              $row[] = (string) $e->getAuthor();

              $row[] = (string) $this->container->get('pi_app_admin.twig.extension.tool')->statusFilter($e);
              
           	  if (is_object($e->getCreatedAt())) {
              	$row[] = (string) $e->getCreatedAt()->format('Y-m-d');
              } else {
              	$row[] = "";
              }
              if (is_object($e->getPublishedAt())) {
                  $row[] = (string) $e->getPublishedAt()->format('Y-m-d');
              } else {
                  $row[] = "";
              }              
              if (is_object($e->getArchiveAt())) {
              	$row[] = (string) $e->getArchiveAt()->format('Y-m-d');
              } else {
              	$row[] = "";
              }
              // create action links
              if($url_edit !=''){
              	$actions  = '<a href="'.$url_edit.'" title="'.$this->container->get('translator')->trans('pi.grid.action.edit').'" class="button-ui-edit" >'.$this->container->get('translator')->trans('pi.grid.action.edit').'</a>'; //actions
                $actions .= '<a id="showimagescrop" url="'.$url_crop.'" title="'.$this->container->get('translator')->trans('pi.form.label.media.picture').'" class="button-ui-edit" >Crop</a>'; //actions
                $row[] = (string) $actions;                  
              } else {
              	$row[] = "";       
              }

              $output['aaData'][] = $row ;
            }
            $response = new Response(json_encode( $output ));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        }
        if (!$is_Server_side) {
           $entities    = $em->getRepository("PluginsContentBundle:BlocGeneral")->findTranslationsByQuery($locale, $query->getQuery(), 'object', false);
        } else {
           $entities   = null;
        } 

        $rubs     = $em->getRepository("PluginsContentBundle:Rub")->getAllTree($locale);
        $rub  = array();
        foreach ($rubs as $rubrique){
            if ($rubrique->getLvl() == 1) {
                $tmp = array();
                foreach ($rubrique->getChildrens() as $r){
                    $tmp[$r->getId()] =  strip_tags($r->getTitle());
                }
                ksort($tmp);
                $rub[$rubrique->getTitle()] = $tmp;
            }
        }
        ksort($rub);
        
        return $this->render("PluginsContentBundle:BlocGeneral:$template", array(
            'isServerSide' => $is_Server_side,
            'entities'	=> $entities,
            'NoLayout'	=> $NoLayout,
            'category'	=> $category,
        	'type_entity'=> $type_entity,
        	'rubriques' => $rub
        ));
    }

    /**
     * Finds and displays a BlocGeneral entity.
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
        $entity     = $em->getRepository("PluginsContentBundle:BlocGeneral")->findOneByEntity($locale, $id, 'object');
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "show.html.twig"; else $template = "show.html.twig";        

        if (!$entity) {
            throw ControllerException::NotFoundException('BlocGeneral');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:BlocGeneral:$template", array(
            'entity'      => $entity,
            'NoLayout'      => $NoLayout,
            'category'      => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new BlocGeneral entity.
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
        $entity     = new BlocGeneral();
        $form       = $this->createForm(new BlocGeneralType($em, $this->container), $entity, array('show_legend' => false));
        
        $category   = $this->container->get('request')->query->get('category', '');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new.html.twig";   
        
        $entity_cat = $em->getRepository("PiAppGedmoBundle:Category")->find($category);
        if (($entity_cat instanceof \PiApp\GedmoBundle\Entity\Category) && method_exists($entity, 'setCategory'))
            $entity->setCategory($entity_cat);     
        elseif (!empty($category) && method_exists($entity, 'setCategory'))
            $entity->setCategory($category);
        
        $entity->setCreatedAt(new \DateTime());
        $user = $this->container->get('security.context')->getToken()->getUser();
        $entity->setUser($user);
        
        return $this->render("PluginsContentBundle:BlocGeneral:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Creates a new BlocGeneral entity.
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
    
        $entity     = new BlocGeneral();
        $request     = $this->getRequest();
        $form        = $this->createForm(new BlocGeneralType($em, $this->container), $entity, array('show_legend' => false));
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_content_bloc_general', array('NoLayout' => $NoLayout, 'category' => $category)));
        } else {
        	$this->setFlashErrorMessages($form);
        }

        return $this->render("PluginsContentBundle:BlocGeneral:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Displays a form to edit an existing BlocGeneral entity.
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
            $entity    = $em->getRepository("PluginsContentBundle:BlocGeneral")->findOneByEntity($locale, $id, 'object');
        } else {
            $slug    = $this->container->get('bootstrap.RouteTranslator.factory')->getMatchParamOfRoute('slug', $locale, true);
            $entity    = $this->container->get('doctrine')->getManager()->getRepository("PluginsContentBundle:BlocGeneral")->getEntityByField($locale, array('content_search' => array('slug' =>$slug)), 'object');
        }        
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:BlocGeneral")->find($id);
            $entity->addTranslation(new BlocGeneralTranslation($locale));            
        }

        $editForm   = $this->createForm(new BlocGeneralType($em, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:BlocGeneral:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Edits an existing BlocGeneral entity.
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
        $entity     = $em->getRepository("PluginsContentBundle:BlocGeneral")->findOneByEntity($locale, $id, "object"); 
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:BlocGeneral")->find($id);
        }
        
        $editForm   = $this->createForm(new BlocGeneralType($em, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($this->getRequest(), $entity);
        if ($editForm->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_content_bloc_general_edit', array('id' => $id, 'NoLayout' => $NoLayout, 'category' => $category)));
        }

        return $this->render("PluginsContentBundle:BlocGeneral:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Deletes a BlocGeneral entity.
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
            $entity = $em->getRepository("PluginsContentBundle:BlocGeneral")->findOneByEntity($locale, $id, 'object');

            if (!$entity) {
                throw ControllerException::NotFoundException('BlocGeneral');
            }

            try {
                $em->remove($entity);
                $em->flush();
            } catch (\Exception $e) {
                $this->container->get('request')->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
            }
        }

        return $this->redirect($this->generateUrl('admin_content_bloc_general', array('NoLayout' => $NoLayout, 'category' => $category)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

}