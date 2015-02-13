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
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

use Plugins\ContentBundle\Entity\Article;
use Plugins\ContentBundle\Entity\BlocGeneral;
use Plugins\ContentBundle\Form\ArticleType;
use Plugins\ContentBundle\Entity\Translation\ArticleTranslation;

use Symfony\Component\Form\FormError;

/**
 * Article controller.
 *
 *
 * @category   PI_CRUD_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ArticleController extends abstractController
{
    protected $_entityName = "PluginsContentBundle:Article";

    /**
     * Enabled Article entities.
     *
     * @Route("/admin/gedmo/article/enabled", name="admin_gedmo_article_enabledentity_ajax")
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
     * Disable Article entities.
     * 
     * @Route("/admin/gedmo/article/disable", name="admin_gedmo_article_disablentity_ajax")
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
     * Change the position of a Article entity.
     *
     * @Route("/admin/gedmo/article/position", name="admin_gedmo_article_position_ajax")
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
     * Delete a Article entity.
     *
     * @Route("/admin/gedmo/article/delete", name="admin_gedmo_article_deletentity_ajax")
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
     * archive Article entities.
     *
     * @Route("/admin/gedmo/article/archive", name="admin_gedmo_article_archiventity_ajax")
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
     * Lists all Article entities.
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
    		$query    = $em->getRepository("PluginsContentBundle:Article")->setContainer($this->container)->getAllByCategory($category, null, '', 'DESC', false, true, true);
    	} else {
    		$query    = $em->getRepository("PluginsContentBundle:Article")->getAllByCategory($category, null, '', 'ASC', false, true, true);
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
                  $UrlPicture = $this->container->get('pi_app_admin.twig.extension.route')->getMediaUrlFunction($e->getImage(), 'reference', true, $e->getUpdatedAt(), 'gedmo_Article');
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
           $entities    = $em->getRepository("PluginsContentBundle:Article")->findTranslationsByQuery($locale, $query->getQuery(), 'object', false);
        } else {
           $entities   = null;
        }    	

        return $this->render("PluginsContentBundle:Article:$template", array(
            'isServerSide' => $is_Server_side,
            'entities'	=> $entities,
            'NoLayout'	=> $NoLayout,
            'category'	=> $category,
        ));
    }

    /**
     * Finds and displays a Article entity.
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
        $entity     = $em->getRepository("PluginsContentBundle:Article")->findOneByEntity($locale, $id, 'object');
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "show.html.twig"; else $template = "show.html.twig";        

        if (!$entity) {
            throw ControllerException::NotFoundException('Article');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:Article:$template", array(
            'entity'      => $entity,
            'NoLayout'      => $NoLayout,
            'category'      => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new Article entity.
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
        
        $entity     = new Article();
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $blocgeneral = new Blocgeneral();
        $blocgeneral->setCreatedAt(new \DateTime());
        $blocgeneral->setUpdatedAt(new \Datetime());
        $entity->setBlocgeneral($blocgeneral);
        
        $form       = $this->createForm(new ArticleType($em, $this->container), $entity, array('show_legend' => false));
        
        return $this->render("PluginsContentBundle:Article:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Creates a new Article entity.
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
    
        $entity     = new Article();
        $request     = $this->getRequest();
        $form        = $this->createForm(new ArticleType($em, $this->container), $entity, array('show_legend' => false));
        $form->bind($request);

        if(!$form->get('blocgeneral')->get('published_at')->getData()) {
            $form->get('blocgeneral')->get('published_at')->addError(new FormError("Cette valeur ne doit pas être vide"));
        }
//         if(!$form->get('blocgeneral')->get('archive_at')->getData()) {
//             $form->get('blocgeneral')->get('archive_at')->addError(new FormError("Cette valeur ne doit pas être vide"));
//         }

        if ($form->isValid()) {
        		$entity->setTranslatableLocale($locale);
        		$em->persist($entity);
        		$em->flush();
                
                return $this->redirect($this->generateUrl('admin_content_article_edit', array('id' => $entity->getId(), 'NoLayout' => $NoLayout, 'category' => $category)));
        }

        return $this->render("PluginsContentBundle:Article:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Displays a form to edit an existing Article entity.
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
            $entity    = $em->getRepository("PluginsContentBundle:Article")->findOneByEntity($locale, $id, 'object');
        } else {
            $slug    = $this->container->get('bootstrap.RouteTranslator.factory')->getMatchParamOfRoute('slug', $locale, true);
            $entity    = $this->container->get('doctrine')->getManager()->getRepository("PluginsContentBundle:Article")->getEntityByField($locale, array('content_search' => array('slug' =>$slug)), 'object');
        }        
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:Article")->find($id);
            $entity->addTranslation(new ArticleTranslation($locale));            
        }

        $entity->getBlocgeneral()->setUpdatedAt(new \Datetime());
        $editForm   = $this->createForm(new ArticleType($em, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:Article:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Edits an existing Article entity.
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
        $entity     = $em->getRepository("PluginsContentBundle:Article")->findOneByEntity($locale, $id, "object"); 
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:Article")->find($id);
        }

        $editForm   = $this->createForm(new ArticleType($em, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);
        $editForm->bind($this->getRequest(), $entity);

        if(!$editForm->get('blocgeneral')->get('published_at')->getData()) {
            $editForm->get('blocgeneral')->get('published_at')->addError(new FormError("Cette valeur ne doit pas être vide"));
        }
//         if(!$editForm->get('blocgeneral')->get('archive_at')->getData()) {
//             $editForm->get('blocgeneral')->get('archive_at')->addError(new FormError("Cette valeur ne doit pas être vide"));
//         }

        if ($editForm->isValid()) {
       		$entity->setTranslatableLocale($locale);
       		$em->persist($entity);
       		$em->flush();

            return $this->redirect($this->generateUrl('admin_content_article_edit', array('id' => $id, 'NoLayout' => $NoLayout, 'category' => $category)));
        }

        return $this->render("PluginsContentBundle:Article:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Deletes a Article entity.
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
            $entity = $em->getRepository("PluginsContentBundle:Article")->findOneByEntity($locale, $id, 'object');
            if (!$entity) {
                throw ControllerException::NotFoundException('Article');
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
        
        return $this->redirect($this->generateUrl('admin_content_bloc_general', array('NoLayout' => $NoLayout, 'category' => $category, 'type' => 'article')));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Template : Finds and displays a Article entity.
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
            
        $entity = $em->getRepository("PluginsContentBundle:Article")->findOneByEntity($lang, $id, 'object', false);
        
        if (!$entity) {
            throw ControllerException::NotFoundException('Article');
        }
        
        if (method_exists($entity, "getTemplate") && $entity->getTemplate() != "")
            $template = $entity->getTemplate();         
    
        return $this->render("PluginsContentBundle:Article:$template", array(
                'entity'    => $entity,
                'locale'    => $lang,
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
    public function _template_listAction($category = '', $MaxResults = null, $template = '_tmp_list.html.twig', $order = 'DESC', $lang = "")
    {
        $em         = $this->getDoctrine()->getManager();

        if (empty($lang))
            $lang    = $this->container->get('request')->getLocale();
            
        $query        = $em->getRepository("PluginsContentBundle:Article")->getAllByCategory($category, $MaxResults, $order)->getQuery();
        $entities   = $em->getRepository("PluginsContentBundle:Article")->findTranslationsByQuery($lang, $query, 'object', false);                   

        return $this->render("PluginsContentBundle:Article:$template", array(
            'entities' => $entities,
            'cat'       => ucfirst($category),
            'locale'   => $lang,
        ));
    } 

    /**
     * Template : Finds and displays an archive of Article entity.
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
        
        $page     = 1;
        if (isset($_GET['page']) && !empty($_GET['page']))
            $page     = $_GET['page'];
            
        $paginator             = $this->container->get('knp_paginator');
    
        $count                 = $em->getRepository("PluginsContentBundle:Article")->count(1);
        $query_pagination    = $em->getRepository("PluginsContentBundle:Article")->getAllByCategory('', null, $order)->getQuery();
        $query_pagination->setHint('knp_paginator.count', $count);
         
        $pagination = $paginator->paginate(
                $query_pagination,
                $page,    /*page number*/
                $MaxResults        /*limit per page*/
        );
         
        //print_r($pagination);exit;
         
        $query_pagination->setFirstResult(($page-1)*$MaxResults);
        $query_pagination->setMaxResults($MaxResults);
        $query    = $em->getRepository("PluginsContentBundle:Article")->setTranslatableHints($query_pagination, $lang, false);
        $entities = $em->getRepository("PluginsContentBundle:Article")->findTranslationsByQuery($lang, $query, 'object', false);
         
        return $this->render("PluginsContentBundle:Article:$template", array(
                'entities'        => $entities,
                'pagination'    => $pagination,
                'locale'        => $lang,
                'lang'            => $lang,
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
    public function get_linked_contentsAction($id = '', $template = '_tmp_list.html.twig', $lang = "")
    {
        $em         = $this->getDoctrine()->getManager();

        if (empty($lang))
            $lang    = $this->container->get('request')->getLocale();
        
        $slug    = $this->container->get('bootstrap.RouteTranslator.factory')->getMatchParamOfRoute('slug', $lang);
        if(empty($slug))
            $slug    = $this->container->get('bootstrap.RouteTranslator.factory')->getMatchParamOfRoute('slug', $lang, true);
        $entity	= $em->getRepository("PluginsContentBundle:BlocGeneral")->getEntityByField($lang, array('content_search' => array('slug' =>$slug)), 'object');
        $id = $entity->getId();

        $tags = $entity->getTag();
        $list = array();
        foreach($tags as $tag){
            array_push($list, $tag->getId());        
        }    
        $listtags = implode(', ', $list);

        $query_tags  = $em->getRepository("PluginsContentBundle:BlocGeneral")->getAllByFields(array('enabled'=>true), 10, '', 'ASC');
        $query_tags->select('a.id, count(a.id) as nbrTag')
                ->leftJoin('a.page', 'cp')
                ->leftJoin('a.tag', 'ctb')
                ->where("cp.id IS NULL")
                ->andWhere("a.id <> $id")
                ->andWhere("ctb.id IN ($listtags)")
                ->groupBy('a.id')
                ->orderBy('nbrTag','DESC');
        $entities_tags = $query_tags->getQuery()->getResult();

        $list_entities= $entities = array();

        foreach ($entities_tags as $entity_tag) {
            array_push($list_entities, $entity_tag['id']);     
        }
        
        if(count($list_entities) > 3 ){
			$rand_keys = array_rand($list_entities, 3);
		}
        else{
			$rand_keys = $list_entities;
		}
		
        foreach($rand_keys as $key){
			array_push($entities, $list_entities[$key]);     
		}

        $query  = $em->getRepository("PluginsContentBundle:BlocGeneral")->getAllByFields(array('enabled'=> true));
        $query->where($query->expr()->in('a.id', $entities));
        
        $results = $em->getRepository("PluginsContentBundle:BlocGeneral")->findTranslationsByQuery($lang, $query->getQuery(), "object", false);

        return $this->render("PluginsContentBundle:Article:$template", array(
            'entity' => $entity,
            'entities' => $results,
            'locale'   => $lang,
        ));
    }

    public function show_images_cropAction($id){

        $em         = $this->getDoctrine()->getManager();
        $locale        = $this->container->get('request')->getLocale();
        $entity     = $em->getRepository("PluginsContentBundle:Article")->findOneByEntity($locale, $id, "object");

        $template = "show_images_crop.html.twig";

        return $this->render("PluginsContentBundle:Article:$template", array(
            'entity' => $entity,
        ));

    }

    /* SEO - liste les 6 derniers articles */
    public function last_articlesAction($template = 'list.html.twig', $lang = ""){

        $em         = $this->getDoctrine()->getManager();
        $locale        = $this->container->get('request')->getLocale();

        $query_article = $em->getRepository("PluginsContentBundle:Article")->createQueryBuilder('a')->select('a');
        $query_article
            ->leftJoin('a.blocgeneral', 'b')
            ->orderBy('b.published_at', 'DESC')
            ->where("b.enabled = 1")
            ->setMaxResults(6);

        $articles = $query_article->getQuery()->getResult();

        return $this->render("PluginsContentBundle:Article:$template", array(
            'articles' => $articles,
        ));
    }
    
    /**
     * Generate the article feed.
     *
     * @Route("/article/feed", name="plugin_content_article_feed")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function feedAction()
    {
    	$articles = $this->getDoctrine()->getRepository('PluginsContentBundle:Article')->findAll();
    
    	$rss = "";
    	// On écrit le prologue du flux RSS 2.0 :
    	$rss .= '<?xml version="1.0" encoding="ISO-8859-15"?>'."\n";
    	$rss .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'."\n";
    	// le channel est l'entête du flux ... on y renseigne :
    	$rss .= '   <channel>'."\n";
    	// le titre du site (attention aux caractères spéciaux ...)
    	$rss .= '       <title>L\'Observatoire de la communication managériale d\'entreprise par Marie de Broisia</title>'."\n";
    	// l'URL (adresse) du site
    	$rss .= '       <link>http://www.mariedebroissia.fr</link>'."\n";
    	// la description du site (attention aux caractères spéciaux ...)
    	$rss .= '       <description>UNE CONVICTION : la performance de l’entreprise passe par une vision partagée et des projets d’entreprise portés par tous. MODE D\'INTERVENTION : je travaille en collaboration avec vos équipes engagées dans la résolution des problématiques de relation et communication en interne (fonction RH, fonction communication) et en complément de vos partenaires externes. </description>'."\n";
    	// la langue du flux
    	$rss .= '       <language>fr</language>'."\n";
    	// la date de publication
    	$rss .= '       <pubDate>'.date('D, d M Y H:i:s O').'</pubDate>'."\n";
    	// la date de construction du flux
    	$rss .= '       <lastBuildDate>'.gmdate('D, d M Y H:i:s').' GMT</lastBuildDate>'."\n";
    	// 2 lignes pour l'auteur du document
    	$rss .= '       <managingEditor>contact@mariedebroissia.fr</managingEditor>'."\n";
    	$rss .= '       <webMaster>contact@pi-groupe.fr</webMaster>'."\n";
    	$rss .= '       <ttl>5</ttl>'."\n";
    	// cette ligne est très importante, elle DOIT être l'URL de la page du flux
    	$rss .= '       <atom:link href="http://www.mariedebroissia.fr/article/feed" rel="self" type="application/rss+xml" />'."\n";
    	// insert DATA
    	foreach($articles as $key => $article)
    	{
    		$rss .= '       <item>'."\n";
    		$rss .= '           <title>'.htmlspecialchars(html_entity_decode($article->getBlocgeneral()->getTitle()), ENT_NOQUOTES).'</title>'."\n";
    		$rss .= '           <description>'.htmlspecialchars($article->getBlocgeneral()->getDescriptif(), ENT_NOQUOTES).'</description>'."\n";
    		$rss .= '           <pubDate>'.date('D, d M Y H:i:s O', $article->getBlocgeneral()->getPublishedAt()->getTimestamp()).'</pubDate>'."\n";
    		$rss .= '       </item>'."\n";
    	};
    	// RSS end
    	$rss .= '   </channel>'."\n";
    	$rss .= '</rss>'."\n";
    
    	return new Response($rss);
    }    
}