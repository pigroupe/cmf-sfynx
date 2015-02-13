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
namespace PiApp\GedmoBundle\Controller;

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

use PiApp\GedmoBundle\Entity\Category;
use PiApp\GedmoBundle\Form\CategoryType;
use PiApp\GedmoBundle\Entity\Translation\CategoryTranslation;

/**
 * Category controller.
 *
 *
 * @category   PI_CRUD_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class CategoryController extends abstractController
{
    protected $_entityName = "PiAppGedmoBundle:Category";

    /**
     * Enabled Category entities.
     *
     * @Route("/admin/gedmo/category/enabled", name="admin_gedmo_category_enabledentity_ajax")
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
     * Disable Category entities.
     * 
     * @Route("/admin/gedmo/category/disable", name="admin_gedmo_category_disablentity_ajax")
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
     * Position Category entities.
     *
     * @Route("/admin/gedmo/category/position", name="admin_gedmo_category_position_ajax")
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
     * Delete Category entities.
     *
     * @Route("/admin/gedmo/category/delete", name="admin_gedmo_category_deletentity_ajax")
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
     * Archive a Category entity.
     *
     * @Route("/admin/gedmo/category/archive", name="admin_gedmo_category_archiveentity_ajax")
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
     * @Route("/content/gedmo/category/select/{type}", name="admin_gedmo_category_selectentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function selectajaxAction($type)
    {
    	$request = $this->container->get('request');
    	$em      = $this->getDoctrine()->getManager();
    	$locale  = $this->container->get('request')->getLocale();
    	//
    	$pagination = $this->container->get('request')->get('pagination', null);
    	$keyword    = $this->container->get('request')->get('keyword', '');
    	$MaxResults = $this->container->get('request')->get('max', 10);
    	// we set query
        $query  = $em->getRepository("PiAppGedmoBundle:Category")->getAllByCategory('', null, '', 'ASC', false);
        $query
        ->andWhere("a.type = '{$type}'");    		
        //
        $keyword = array(
            0 => array(
                'field_name' => 'name',
                'field_value' => $keyword,
                'field_trans' => true,
                'field_trans_name' => 'trans',
            ),
        );

        return $this->selectajaxQuery($pagination, $MaxResults, $keyword, $query, $locale, true, array(
            'time'      => 3600,  
            'namespace' => 'hash_list_gedmo_category'
        ));
    }     
    
    /**
     * Select all entities.
     *
     * @return Response
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function renderselectajaxQuery($entities, $locale)
    {
    	$tab = array();
    	foreach ($entities as $obj) {
            $content   = $obj->translate($locale)->getName();
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
     * Lists all Category entities.
     *
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function indexAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $locale        = $this->container->get('request')->getLocale();
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "index.html.twig"; else $template = "index.html.twig";
        
        if ($NoLayout){
        	$query    = $em->getRepository("PiAppGedmoBundle:Category")->setContainer($this->container)->getAllByCategory('', null, '', 'DESC', false);
        } else {
        	$query    = $em->getRepository("PiAppGedmoBundle:Category")->getAllByCategory('', null, '', 'ASC', false);
        }
        $qb     = $em->getRepository($this->_entityName)->cacheQuery(
                $query->getQuery(), 
                3600, 
                3, 
                true,
                'hash_list_gedmo_category'
        );
        $entities   = $em->getRepository("PiAppGedmoBundle:Category")->findTranslationsByQuery($locale, $qb, 'object', false, true);
    
        return $this->render("PiAppGedmoBundle:Category:$template", array(
                'entities' => $entities,
                'NoLayout'    => $NoLayout,
        ));
    }    
    
    /**
     * Finds and displays a Category entity.
     *
     * @PreAuthorize("hasRole('ROLE_EDITOR') or (hasRole('ROLE_ADMIN') and hasRole('ROLE_SUPER_ADMIN'))")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function showAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $entity = $em->getRepository("PiAppGedmoBundle:Category")->findOneByEntity($locale, $id, 'object');
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "show.html.twig"; else $template = "show.html.twig";        

        if (!$entity) {
            throw ControllerException::NotFoundEntity('Category');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PiAppGedmoBundle:Category:$template", array(
            'entity'      => $entity,
            'NoLayout'      => $NoLayout,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Category entity.
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
        $type   = $this->container->get('request')->query->get('type');
        $entity = new Category();
        $entity->setType($type);
        $entity->setUpdatedAt(new \Datetime());
        $form   = $this->createForm(new CategoryType($this->container, $em), $entity, array('show_legend' => false));
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new.html.twig";        

        return $this->render("PiAppGedmoBundle:Category:$template", array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'NoLayout'  => $NoLayout,
        ));
    }

    /**
     * Creates a new Category entity.
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
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new.html.twig";        
    
        $entity  = new Category();
        $request = $this->getRequest();
        $form    = $this->createForm(new CategoryType($this->container, $em), $entity, array('show_legend' => false));
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();
            // to delete cache list query
            $this->deleteAllCacheQuery('hash_list_gedmo_category');
            
            return $this->redirect($this->generateUrl('admin_gedmo_category_show', array('id' => $entity->getId(), 'NoLayout' => $NoLayout)));
                        
        }

        return $this->render("PiAppGedmoBundle:Category:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
        ));
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function editAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $entity = $em->getRepository("PiAppGedmoBundle:Category")->findOneByEntity($locale, $id, 'object');
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PiAppGedmoBundle:Category")->find($id);
            $entity->addTranslation(new CategoryTranslation($locale));            
        }

        $editForm   = $this->createForm(new CategoryType($this->container, $em), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PiAppGedmoBundle:Category:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
        ));
    }

    /**
     * Edits an existing Category entity.
     *
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>   
     */
    public function updateAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $entity = $em->getRepository("PiAppGedmoBundle:Category")->findOneByEntity($locale, $id, "object"); 
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PiAppGedmoBundle:Category")->find($id);
        }

        $editForm   = $this->createForm(new CategoryType($this->container, $em), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($this->getRequest(), $entity);
        if ($editForm->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();
            // to delete cache list query
            $this->deleteAllCacheQuery('hash_list_gedmo_category');

            return $this->redirect($this->generateUrl('admin_gedmo_category_edit', array('id' => $id, 'NoLayout' => $NoLayout)));
        }

        return $this->render("PiAppGedmoBundle:Category:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
        ));
    }

    /**
     * Deletes a Category entity.
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
    
        $form      = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $entity = $em->getRepository("PiAppGedmoBundle:Category")->findOneByEntity($locale, $id, 'object');

            if (!$entity) {
                throw ControllerException::NotFoundEntity('Category');
            }

            try {
                $em->remove($entity);
                $em->flush();
                // to delete cache list query
                $this->deleteAllCacheQuery('hash_list_gedmo_category');
            } catch (\Exception $e) {
                $this->container->get('request')->getSession()->getFlashBag()->clear();
                $this->container->get('request')->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
            }
        }

        return $this->redirect($this->generateUrl('admin_gedmo_category', array('NoLayout' => $NoLayout)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Template : Finds and displays a Category entity.
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
        
        $entity = $em->getRepository("PiAppGedmoBundle:Category")->findOneByEntity($lang, $id, 'object', false);
        
        if (!$entity) {
            throw ControllerException::NotFoundEntity('Category');
        }
    
        return $this->render("PiAppGedmoBundle:Category:$template", array(
                'entity'      => $entity,
                'locale'   => $lang,
                'lang'       => $lang,
        ));
    }

    /**
     * Template : Finds and displays a list of Category entity.
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
        
        $query        = $em->getRepository("PiAppGedmoBundle:Category")->getAllByCategory($category, $MaxResults, $order)->getQuery();
        $entities   = $em->getRepository("PiAppGedmoBundle:Category")->findTranslationsByQuery($lang, $query, 'object', false);                   

        return $this->render("PiAppGedmoBundle:Category:$template", array(
            'entities' => $entities,
            'cat'       => ucfirst($category),
            'locale'   => $lang,
            'lang'       => $lang,
        ));
    }     
    
}
