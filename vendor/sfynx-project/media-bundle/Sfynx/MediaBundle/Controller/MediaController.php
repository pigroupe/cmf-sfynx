<?php
/**
 * This file is part of the <Media> project.
 *
 * @subpackage   Media
 * @package    Controller
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2014-08-23
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sfynx\AuthBundle\Controller\abstractController;
use Sfynx\ToolBundle\Exception\ControllerException;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Sfynx\MediaBundle\Entity\Media;
use Sfynx\MediaBundle\Entity\Translation\MediaTranslation;

/**
 * Media controller.
 *
 *
 * @subpackage   Media
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class MediaController extends abstractController
{
    protected $_entityName = "SfynxMediaBundle:Media";

    /**
     * Enabled Media entities.
     *
     * @Route("/content/sfynx/media/enabled", name="sfynx_media_enabledentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
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
     * Disable Media entities.
     * 
     * @Route("/content/sfynx/media/disable", name="sfynx_media_disablentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
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
     * Position Media entities.
     *
     * @Route("/content/sfynx/media/position", name="sfynx_media_position_ajax")
     * @Secure(roles="ROLE_EDITOR")
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
     * Delete Media entities.
     *
     * @Route("/content/sfynx/media/delete", name="sfynx_media_deletentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
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
     * Archive a Media entity.
     *
     * @Route("/content/sfynx/media/archive", name="sfynx_media_archiveentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
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
     * @Route("/content/sfynx/media/select/{type}", name="sfynx_media_selectentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access  public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function selectajaxAction($type)
    {
    	$request = $this->container->get('request');
    	$em		 = $this->getDoctrine()->getManager();
    	$locale  = $this->container->get('request')->getLocale();
    	//
    	$pagination = $this->container->get('request')->get('pagination', null);
    	$keyword    = $this->container->get('request')->get('keyword', '');
    	$MaxResults = $this->container->get('request')->get('max', 10);
    	// we set query
   		$query  = $em->getRepository("SfynxMediaBundle:Media")->getAllByCategory('', null, '', '', false);
   		$query
   		->leftJoin('a.translations', 'trans')
   		->leftJoin('a.image', 'm')
   		->andWhere('a.image IS NOT NULL')
   		->andWhere("a.status = '{$type}'");    		
   		//
  		$keyword = array(
			0 => array(
				'field_name' => 'title',
   				'field_value' => $keyword,
			    'field_trans' => true,
			    'field_trans_name' => 'trans',
			),
   		);
  		// we set type value
  		$this->type = $type;
    		
   		return $this->selectajaxQuery($pagination, $MaxResults, $keyword, $query, $locale, true, array(
              'time'      => 3600,  
              'namespace' => 'hash_list_gedmomedia'
        ));
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
    			$content = $obj->getId();
    			$title   = $obj->translate($locale)->getTitle();
    			$cat     = $obj->getCategory();
    			if ($title) {
    				$content .=  " - " .$title;
    			}
    			if (!is_null($cat)) {
    				$content .=  '('. $cat->translate($locale)->getName() .')';
    			}
    			if ( ($this->type == 'image') && ($obj->getImage() instanceof \BootStrap\MediaBundle\Entity\Media)) {
    				$content .= "<img width='100px' src=\"{{ media_url('".$obj->getImage()->getId()."', 'small', true, '".$obj->getUpdatedAt()->format('Y-m-d H:i:s')."', 'gedmo_media_') }}\" alt='Photo'/>";
    			}
    			$tab[] = array(
    					'id' => $obj->getId(),
    					'text' =>$this->container->get('twig')->render($content, array())
    			);
    		}
    
    	return $tab;
    }    

    /**
     * Lists all Media entities.
     *
     * @Secure(roles="IS_AUTHENTICATED_ANONYMOUSLY")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function indexAction()
    {
        $em      = $this->getDoctrine()->getManager();
        $request = $this->container->get('request');
        $locale  = $request->getLocale();
        // we get params
        $NoLayout   = $request->query->get('NoLayout');
        $category   = $request->query->get('category');
        if (is_array($category) && isset($category['__isInitialized__'])) {
            $category = $category['__isInitialized__'];
        }
        // weg set query
        $query = $em->getRepository("SfynxMediaBundle:Media")->getAllByCategory($category, null, '', '', false);
        $query
        ->leftJoin('a.image', 'm')
        ->leftJoin('a.category', 'c')
        ->andWhere('a.image IS NOT NULL');
        //
        $is_Server_side = true;
        //
        if ($request->isXmlHttpRequest() && $is_Server_side) {
           $aColumns    = array('a.id','c.name','a.status','a.title',"a.enabled",'a.created_at', 'a.updated_at',"a.enabled","a.enabled");
           $q1 = clone $query;
           $q2 = clone $query;
           $result    = $this->createAjaxQuery('select',$aColumns, $q1, 'a', null, array(
                            0 =>array('column'=>'a.created_at', 'format'=>'Y-m-d', 'idMin'=>'minc', 'idMax'=>'maxc'),
                            1 =>array('column'=>'a.updated_at', 'format'=>'Y-m-d', 'idMin'=>'minu', 'idMax'=>'maxu')
                      ), array(
                            'time'      => 3600,  
                            'namespace' => 'hash_list_gedmomedia'
                      )                      
           );
           $total    = $this->createAjaxQuery('count',$aColumns, $q2, 'a', null, array(
                            0 =>array('column'=>'a.created_at', 'format'=>'Y-m-d', 'idMin'=>'minc', 'idMax'=>'maxc'),
                            1 =>array('column'=>'a.updated_at', 'format'=>'Y-m-d', 'idMin'=>'minu', 'idMax'=>'maxu')
                      ), array(
                            'time'      => 3600,
                            'namespace' => 'hash_list_gedmomedia'
                      )
           );
        
           $output = array(
                "sEcho" => intval($request->get('sEcho')),
                "iTotalRecords" => $total,
                "iTotalDisplayRecords" => $total,
                "aaData" => array()
           );
        
           foreach ($result as $e) {
              $row = array();
              $row[] = $e->getId() . '_row_' . $e->getId();
              $row[] = $e->getId();
              
              if (is_object($e->getCategory())) {
                  $row[] = (string) $e->getCategory()->getName();
              } else {
                  $row[] = "";
              }
              
              $row[] = (string) $e->getStatus() . '('.$e->getId().')';
              
              if (is_object($e->getImage())) {
            	  $title = $e->getTitle();
              	  if (!empty($title)) {
              			$row[] = (string) $title . '('. $e->getImage()->getName() . ')';
              	  } else {
              			$row[] = (string) $e->getImage()->getName();
              	  }
                  $url = $this->container->get('sfynx.tool.twig.extension.route')->getMediaUrlFunction($e->getImage(), 'reference', true, $e->getUpdatedAt(), 'media_');
              } else {
                  $row[] = "";
                  $url = "#";
              }
              
              if ($e->getStatus() == 'image') {
              	$UrlPicture = $this->container->get('sfynx.tool.twig.extension.route')->getMediaUrlFunction($e->getImage(), 'reference', true, $e->getUpdatedAt(), 'gedmo_media_');
              	$row[] = (string) '<a href="#" title=\'<img src="'.$UrlPicture.'" class="info-tooltip-image" >\' class="info-tooltip"><img width="20px" src="'.$UrlPicture.'"></a>';
              } else {
              	$row[] = "";
              }
              
              if (is_object($e->getCreatedAt())) {
              	$row[] = (string) $e->getCreatedAt()->format('Y-m-d');
              } else {
              	$row[] = "";
              }
              
              if (is_object($e->getUpdatedAt())) {
                  $row[] = (string) $e->getUpdatedAt()->format('Y-m-d');
              } else {
                  $row[] = "";
              }
              // create enabled/disabled buttons
              $Urlenabled     = $this->container->get('templating.helper.assets')->getUrl($this->container->getParameter('sfynx.auth.theme.layout.admin.grid.img')."enabled.png");
              $Urldisabled     = $this->container->get('templating.helper.assets')->getUrl($this->container->getParameter('sfynx.auth.theme.layout.admin.grid.img')."disabled.png");
              if ($e->getEnabled()) {
                  $row[] = (string) '<img width="17px" src="'.$Urlenabled.'">';
              } else {
                  $row[] = (string) '<img width="17px" src="'.$Urldisabled.'">';
              }
              // create action links
              $route_path_show = $this->container->get('sfynx.tool.twig.extension.route')->getUrlByRouteFunction('sfynx_media_show', array('id'=>$e->getId(), 'NoLayout'=>$NoLayout, 'category'=>$category, 'status'=>$e->getStatus()));
              $route_path_edit = $this->container->get('sfynx.tool.twig.extension.route')->getUrlByRouteFunction('sfynx_media_edit', array('id'=>$e->getId(), 'NoLayout'=>$NoLayout, 'category'=>$category, 'status'=>$e->getStatus()));
              if (is_object($e->getImage()) && ($e->getStatus() == 'image')) {
                  $actions = '<a href="'.$route_path_show.'" title="'.$this->container->get('translator')->trans('pi.grid.action.show').'" data-ui-icon="ui-icon-show" class="button-ui-icon-show info-tooltip" >'.$this->container->get('translator')->trans('pi.grid.action.show').'</a>'; //actions
              } else {
                  $actions = '<a href="'.$url.'" target="_blank" title="'.$this->container->get('translator')->trans('pi.grid.action.show').'" data-ui-icon="ui-icon-show" class="button-ui-icon-show info-tooltip" >'.$this->container->get('translator')->trans('pi.grid.action.show').'</a>'; //actions
              }              
              $actions .= '<a href="'.$route_path_edit.'" title="'.$this->container->get('translator')->trans('pi.grid.action.edit').'" data-ui-icon="ui-icon-edit" class="button-ui-icon-edit info-tooltip" >'.$this->container->get('translator')->trans('pi.grid.action.edit').'</a>'; //actions
              $row[] = (string) $actions;
              
              $output['aaData'][] = $row ;
            }
            $response = new Response(json_encode( $output ));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        }
        if (!$is_Server_side) {
            if ($this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            } else {
                $query = $em->getRepository("SfynxMediaBundle:Media")->setContainer($this->container)->checkRoles($query);
            }
            $query     = $em->getRepository("SfynxMediaBundle:Media")->cacheQuery($query->getQuery(), 3600, 3 /*\Doctrine\ORM\Cache::MODE_NORMAL */, true, 'hash_list_gedmomedia');
            $entities  = $em->getRepository("SfynxMediaBundle:Media")->findTranslationsByQuery($locale, $query, 'object', false);
        } else {
            $entities  = null;
        }
        
        return $this->render("SfynxMediaBundle:Media:index.html.twig", array(
            'isServerSide' => $is_Server_side,
            'entities' => $entities,
            'NoLayout'    => $NoLayout,
            'category' => $category,
        ));
    }
    
    /**
     * Finds and displays a Media entity.
     *
     * @Secure(roles="IS_AUTHENTICATED_ANONYMOUSLY")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function showAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $entity = $em->getRepository("SfynxMediaBundle:Media")->findOneByEntity($locale, $id, 'object');
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "show.html.twig"; else $template = "show.html.twig";

        $category   = $this->container->get('request')->query->get('category');
        if (is_array($category) && isset($category['__isInitialized__']))
            $category = $category['__isInitialized__'];

        if (!$entity) {
            throw ControllerException::NotFoundEntity('Media');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render("SfynxMediaBundle:Media:$template", array(
            'entity'      => $entity,
            'NoLayout'      => $NoLayout,
            'category'      => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Displays a form to create a new Media entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function newAction()
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $status = $this->container->get('request')->query->get('status');
        $entity = new Media();
        $entity->setStatus($status);
        $entity->setUpdatedAt(new \Datetime());
        //$form   = $this->createForm(new MediaType($this->container, $em, $status), $entity, array('show_legend' => false));
        $form   = $this->createForm('sfynx_mediabundle_mediatype_' . $status, $entity, array('show_legend' => false));
    
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new.html.twig";
        
        $category   = $this->container->get('request')->query->get('category');
        if (is_array($category) && isset($category['__isInitialized__']))
            $category = $category['__isInitialized__'];
    
        return $this->render("SfynxMediaBundle:Media:$template", array(
                'entity' => $entity,
                'form'   => $form->createView(),
                'NoLayout'  => $NoLayout,
                'category'      => $category,
                'status'    => $status,
        ));
    }
    
    /**
     * Creates a new Media entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function createAction()
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $status = $this->container->get('request')->query->get('status');
    
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new.html.twig";
        
        $category   = $this->container->get('request')->query->get('category');
        if (is_array($category) && isset($category['__isInitialized__']))
            $category = $category['__isInitialized__'];
    
        $entity  = new Media();
        $entity->setStatus($status);
        $request = $this->getRequest();
        $form    = $this->createForm('sfynx_mediabundle_mediatype_' . $status, $entity, array('show_legend' => false));
        $form->bind($request);
    
        if ($form->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();
            // to delete cache list query
            $this->deleteAllCacheQuery('hash_list_gedmomedia');
            
            return $this->redirect($this->generateUrl('sfynx_media_show', array('id' => $entity->getId(), 'NoLayout' => $NoLayout, 'category' => $category)));
        }
    
        return $this->render("SfynxMediaBundle:Media:$template", array(
                'entity'     => $entity,
                'form'       => $form->createView(),
                'NoLayout'  => $NoLayout,
                'category'      => $category,
                'status'    => $status,
        ));
    }    

    /**
     * Displays a form to edit an existing Media entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function editAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $entity = $em->getRepository("SfynxMediaBundle:Media")->findOneByEntity($locale, $id, 'object');
        
        $status        = $this->container->get('request')->query->get('status');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";

        $category   = $this->container->get('request')->query->get('category');
        if (is_array($category) && isset($category['__isInitialized__']))
            $category = $category['__isInitialized__'];

        if (!$entity) {
            $entity = $em->getRepository("SfynxMediaBundle:Media")->find($id);
            $entity->addTranslation(new MediaTranslation($locale));            
        }

        $entity->setUpdatedAt(new \Datetime());
        $editForm   = $this->createForm('sfynx_mediabundle_mediatype_' . $status, $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render("SfynxMediaBundle:Media:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'category'      => $category,
            'status'      => $status,
        ));
    }

    /**
     * Edits an existing Media entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>   
     */
    public function updateAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale = $this->container->get('request')->getLocale();
        $entity = $em->getRepository("SfynxMediaBundle:Media")->findOneByEntity($locale, $id, "object"); 
        
        $status        = $this->container->get('request')->query->get('status');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";

        $category   = $this->container->get('request')->query->get('category');
        if (is_array($category) && isset($category['__isInitialized__']))
            $category = $category['__isInitialized__'];

        if (!$entity) {
            $entity = $em->getRepository("SfynxMediaBundle:Media")->find($id);
        }

        $editForm   = $this->createForm('sfynx_mediabundle_mediatype_' . $status, $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($this->getRequest(), $entity);
        if ($editForm->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();
            // to delete cache list query
            $this->deleteAllCacheQuery('hash_list_gedmomedia');

            return $this->redirect($this->generateUrl('sfynx_media_edit', array('id' => $id, 'NoLayout' => $NoLayout, 'category' => $category, 'status' => $status)));
        }

        return $this->render("SfynxMediaBundle:Media:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'status'    => $status,
            'category'      => $category,
        ));
    }

    /**
     * Deletes a Media entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *     
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>     
     */
    public function deleteAction($id)
    {
        $em      = $this->getDoctrine()->getManager();
        $locale     = $this->container->get('request')->getLocale();
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');       
        $category   = $this->container->get('request')->query->get('category');
    
        $form      = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $entity = $em->getRepository("SfynxMediaBundle:Media")->findOneByEntity($locale, $id, 'object');
            if (!$entity) {
                throw ControllerException::NotFoundEntity('Media');
            }
            try {
                $em->remove($entity);
                $em->flush();                
                // to delete cache list query
                $this->deleteAllCacheQuery('hash_list_gedmomedia');
            } catch (\Exception $e) {
                $this->container->get('request')->getSession()->getFlashBag()->clear();
                $this->container->get('request')->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
            }
        }

        return $this->redirect($this->generateUrl('sfynx_media', array('NoLayout' => $NoLayout, 'category' => $category)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Template : Finds and displays a Media entity.
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
        
        $entity = $em->getRepository("SfynxMediaBundle:Media")->findOneByEntity($lang, $id, 'object', false);
        
        if (!$entity) {
            throw ControllerException::NotFoundEntity('Media');
        }
    
        return $this->render("SfynxMediaBundle:Media:$template", array(
                'entity'      => $entity,
                'locale'   => $lang,
                'lang'       => $lang,
        ));
    }

    /**
     * Template : Finds and displays a list of Media entity.
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
            $lang   = $this->container->get('request')->getLocale();
        
        $query      = $em->getRepository("SfynxMediaBundle:Media")->getAllByCategory($category, $MaxResults, '', $order)->getQuery();
        $entities   = $em->getRepository("SfynxMediaBundle:Media")->findTranslationsByQuery($lang, $query, 'object', false);                   

        return $this->render("SfynxMediaBundle:Media:$template", array(
            'entities' => $entities,
            'cat'       => ucfirst($category),
            'locale'   => $lang,
            'lang'       => $lang,
        ));
    }     
    
}
