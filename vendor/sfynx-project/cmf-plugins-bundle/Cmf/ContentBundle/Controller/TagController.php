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

use Cmf\ContentBundle\Entity\Tag;
use Cmf\ContentBundle\Form\TagType;
use Cmf\ContentBundle\Entity\Translation\TagTranslation;
use Symfony\Component\Form\FormError;

/**
 * Tag controller.
 *
 *
 * @category   PI_CRUD_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class TagController extends abstractController
{
    protected $_entityName = "PluginsContentBundle:Tag";

    /**
     * Enabled Tag entities.
     *
     * @Route("/admin/content/tag/enabled", name="admin_content_tag_enabledentity_ajax")
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
     * Disable Tag entities.
     * 
     * @Route("/admin/content/tag/disable", name="admin_content_tag_disablentity_ajax")
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
     * Change the position of a Tag entity.
     *
     * @Route("/admin/content/tag/position", name="admin_content_tag_position_ajax")
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
     * Delete a Tag entity.
     *
     * @Route("/admin/content/tag/delete", name="admin_content_tag_deletentity_ajax")
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
     * archive Tag entities.
     *
     * @Route("/admin/content/tag/archive", name="admin_content_tag_archiventity_ajax")
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
     * get entities in ajax request for select form.
     *
     * @Route("/admin/content/tag/select", name="admin_content_tag_selectentity_ajax")
     * @Secure(roles="ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function selectajaxAction()
    {
    	$request = $this->container->get('request');
    	$em		 = $this->getDoctrine()->getManager();
    	$locale  = $this->container->get('request')->getLocale();
		//
    	$pagination = $this->container->get('request')->get('pagination', null);
    	$keyword    = $this->container->get('request')->get('keyword', '');
    	$MaxResults = $this->container->get('request')->get('max', 10);
    	// we set query    
    	$query  = $em->getRepository("PluginsContentBundle:Tag")->getAllByCategory('', null, '', '', false);
    	$query
    	->leftJoin('a.translations', 'trans');
   		//
  		$keyword = array(
			0 => array(
				'field_name' => 'title',
   				'field_value' => $keyword,
				'field_trans' => true,
				'field_trans_name' => 'trans',
			),
   		);
    		
   		return $this->selectajaxQuery($pagination, $MaxResults, $keyword, $query, $locale, true);
    }
    
    /**
     * Select all entities.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access  protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function renderselectajaxQuery($entities, $locale)
    {
    	$tab = array();
    	foreach ($entities as $obj) {
    		$content   = $obj->translate($locale)->getTitle();
    		if (!empty($content)) {
    			$tab[] = array(
    					'id' => $obj->getId(),
    					'text' =>$this->container->get('twig')->render($content, array())
    			);
    		}
    	}
    	 
    	return $tab;
    }    
        
    /**
     * Lists all Tag entities.
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
    		$query    = $em->getRepository("PluginsContentBundle:Tag")->setContainer($this->container)->getAllByCategory($category, null, '', '', false);
    	} else {
    		$query    = $em->getRepository("PluginsContentBundle:Tag")->getAllByCategory($category, null, '', '', false);
    	}
    	//
        $is_Server_side = true;
        //        
        if ($request->isXmlHttpRequest() && $is_Server_side) {
           $aColumns    = array('a.id','a.title','a.created_at','a.enabled',"a.enabled");
           $q1 = clone $query;
           $q2 = clone $query;
           $result    = $this->createAjaxQuery('select',$aColumns, $q1, 'a', null, array(
                            0 =>array('column'=>'a.created_at', 'format'=>'Y-m-d', 'idMin'=>'minc', 'idMax'=>'maxc'),
                      )
           );
           $total    = $this->createAjaxQuery('count',$aColumns, $q2, 'a', null, array(
                            0 =>array('column'=>'a.created_at', 'format'=>'Y-m-d', 'idMin'=>'minc', 'idMax'=>'maxc'),
                      )
           );
        
           $output = array(
               "sEcho" => intval($request->get('sEcho')),
                "iTotalRecords" => intval($total),
                "iTotalDisplayRecords" => intval($total),
                "aaData" => array()
           );
        
           foreach ($result as $e) {
              $row = array();
              $row[] = (string) $e->getId() . '_row_' . $e->getId();
              $row[] = (string) $e->getId();
              
              $row[] = (string) $e->getTitle();
              
              if (is_object($e->getCreatedAt())) {
              	$row[] = (string) $e->getCreatedAt()->format('Y-m-d');
              } else {
              	$row[] = "";
              }

              // create enabled/disabled buttons
              $Urlenabled     = $this->container->get('templating.helper.assets')->getUrl("bundles/piappadmin/css/themes/img/enabled.png");
              $Urldisabled     = $this->container->get('templating.helper.assets')->getUrl("bundles/piappadmin/css/themes/img/disabled.png");
              if ($e->getEnabled()) {
                  $row[] = (string) '<img width="17px" src="'.$Urlenabled.'">';
              } else {
                  $row[] = (string) '<img width="17px" src="'.$Urldisabled.'">';
              }
              // create action links
              $route_path_show = $this->container->get('pi_app_admin.twig.extension.route')->getUrlByRouteFunction('admin_content_tag_show', array('id'=>$e->getId(), 'NoLayout'=>$NoLayout, 'category'=>$category));
              $route_path_edit = $this->container->get('pi_app_admin.twig.extension.route')->getUrlByRouteFunction('admin_content_tag_edit', array('id'=>$e->getId(), 'NoLayout'=>$NoLayout, 'category'=>$category));
              $actions = '<a href="'.$route_path_show.'" title="'.$this->container->get('translator')->trans('pi.grid.action.show').'" class="button-ui-show info-tooltip" >'.$this->container->get('translator')->trans('pi.grid.action.show').'</a>'; //actions
              $actions = '<a href="'.$route_path_edit.'" title="'.$this->container->get('translator')->trans('pi.grid.action.edit').'" class="button-ui-edit info-tooltip" >'.$this->container->get('translator')->trans('pi.grid.action.edit').'</a>'; //actions
              $row[] = (string) $actions;                  

              $output['aaData'][] = $row ;
            }
            $response = new Response(json_encode( $output ));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        }
        
        if (!$is_Server_side) {
           $entities    = $em->getRepository("PluginsContentBundle:Tag")->findTranslationsByQuery($locale, $query->getQuery(), 'object', false);
        } else {
           $entities   = null;
        }    	
        
        return $this->render("PluginsContentBundle:Tag:$template", array(
            'isServerSide' => $is_Server_side,
            'entities'	=> $entities,
            'NoLayout'	=> $NoLayout,
            'category'	=> $category,
        ));
    }

    /**
     * Finds and displays a Tag entity.
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
        $entity     = $em->getRepository("PluginsContentBundle:Tag")->findOneByEntity($locale, $id, 'object');
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "show.html.twig"; else $template = "show.html.twig";        

        if (!$entity) {
            throw ControllerException::NotFoundException('Tag');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:Tag:$template", array(
            'entity'      => $entity,
            'NoLayout'      => $NoLayout,
            'category'      => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new Tag entity.
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
        $entity     = new Tag();
        $form       = $this->createForm(new TagType($em, $this->container), $entity, array('show_legend' => false));
        
        $category   = $this->container->get('request')->query->get('category', '');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new.html.twig";   
        
        $entity_cat = $em->getRepository("PiAppGedmoBundle:Category")->find($category);
        if (($entity_cat instanceof \PiApp\GedmoBundle\Entity\Category) && method_exists($entity, 'setCategory'))
            $entity->setCategory($entity_cat);     
        elseif (!empty($category) && method_exists($entity, 'setCategory'))
            $entity->setCategory($category);
            
        return $this->render("PluginsContentBundle:Tag:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Creates a new Tag entity.
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
    
        $entity     = new Tag();
        $request     = $this->getRequest();
        $form        = $this->createForm(new TagType($em, $this->container), $entity, array('show_legend' => false));
        $form->bind($request);

        $data = $this->getRequest()->request->get($form->getName(), array());

        $tag  = $em->getRepository('PluginsContentBundle:Tag')->getEntityByField($locale, array('content_search' => array('title' => $data["title"])), 'object');
        if ($tag) {
            $form->get('title')->addError(new FormError('Ce tag est déjà existant'));
        }

        if ($form->isValid()) {
            	$entity->setTranslatableLocale($locale);
            	$entity->setEnabled(true);
            	$em->persist($entity);
            	$em->flush();
            	
                return $this->redirect($this->generateUrl('admin_content_tag_edit', array('id'=>$entity->getId(), 'NoLayout'=>$NoLayout)));                
        }

        return $this->render("PluginsContentBundle:Tag:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Displays a form to edit an existing Tag entity.
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
            $entity    = $em->getRepository("PluginsContentBundle:Tag")->findOneByEntity($locale, $id, 'object');
        } else {
            $slug    = $this->container->get('bootstrap.RouteTranslator.factory')->getMatchParamOfRoute('slug', $locale, true);
            $entity    = $this->container->get('doctrine')->getManager()->getRepository("PluginsContentBundle:Tag")->getEntityByField($locale, array('content_search' => array('slug' =>$slug)), 'object');
        }        
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:Tag")->find($id);
            $entity->addTranslation(new TagTranslation($locale));            
        }

        $editForm   = $this->createForm(new TagType($em, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:Tag:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Edits an existing Tag entity.
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
        $entity     = $em->getRepository("PluginsContentBundle:Tag")->findOneByEntity($locale, $id, "object"); 
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "index.html.twig";

        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:Tag")->find($id);
        }

        $editForm   = $this->createForm(new TagType($em, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($this->getRequest(), $entity);

        $data = $this->getRequest()->request->get($editForm->getName(), array());
        $tag  = $em->getRepository('PluginsContentBundle:Tag')->getEntityByField($locale, array('content_search' => array('title' => $data["title"])), 'object');
        if ($tag) {
            $editForm->get('title')->addError(new FormError('Ce tag est déjà existant'));
        }

        if ($editForm->isValid()) {
        		$entity->setTranslatableLocale($locale);
        		$em->persist($entity);
        		$em->flush();

        		return $this->redirect($this->generateUrl('admin_content_tag', array('NoLayout'=>$NoLayout)));
        }

        return $this->render("PluginsContentBundle:Tag:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Deletes a Tag entity.
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
            $entity = $em->getRepository("PluginsContentBundle:Tag")->findOneByEntity($locale, $id, 'object');
            if (!$entity) {
                throw ControllerException::NotFoundException('Tag');
            }

            try {
                	$em->remove($entity);;
                	$em->flush();
            } catch (\Exception $e) {
                $this->container->get('request')->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
            }
        }

        return $this->redirect($this->generateUrl('admin_content_tag', array('NoLayout' => $NoLayout, 'category' => $category)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Template : Finds and displays a Tag entity.
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
            
        $entity = $em->getRepository("PluginsContentBundle:Tag")->findOneByEntity($lang, $id, 'object', false);
        
        if (!$entity) {
            throw ControllerException::NotFoundException('Tag');
        }
        
        if (method_exists($entity, "getTemplate") && $entity->getTemplate() != "")
            $template = $entity->getTemplate();         
    
        return $this->render("PluginsContentBundle:Tag:$template", array(
                'entity'    => $entity,
                'locale'    => $lang,
        ));
    }

    /**
     * Template : Finds and displays a list of Tag entity.
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
            
        $query        = $em->getRepository("PluginsContentBundle:Tag")->getAllByCategory($category, $MaxResults, $order)->getQuery();
        $entities   = $em->getRepository("PluginsContentBundle:Tag")->findTranslationsByQuery($lang, $query, 'object', false);                   

        return $this->render("PluginsContentBundle:Tag:$template", array(
            'entities' => $entities,
            'cat'       => ucfirst($category),
            'locale'   => $lang,
        ));
    } 

    /**
     * Template : Finds and displays an archive of Tag entity.
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
    
        $count                 = $em->getRepository("PluginsContentBundle:Tag")->count(1);
        $query_pagination    = $em->getRepository("PluginsContentBundle:Tag")->getAllByCategory('', null, $order)->getQuery();
        $query_pagination->setHint('knp_paginator.count', $count);
         
        $pagination = $paginator->paginate(
                $query_pagination,
                $page,    /*page number*/
                $MaxResults        /*limit per page*/
        );
         
        //print_r($pagination);exit;
         
        $query_pagination->setFirstResult(($page-1)*$MaxResults);
        $query_pagination->setMaxResults($MaxResults);
        $query_pagination    = $em->getRepository("PluginsContentBundle:Tag")->setTranslatableHints($query_pagination, $lang, false);
        $entities            = $em->getRepository("PluginsContentBundle:Tag")->findTranslationsByQuery($lang, $query_pagination, 'object', false);
         
        return $this->render("PluginsContentBundle:Tag:$template", array(
                'entities'        => $entities,
                'pagination'    => $pagination,
                'locale'        => $lang,
                'lang'            => $lang,
        ));        
    }        
    
}