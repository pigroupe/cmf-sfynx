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

use Plugins\ContentBundle\Entity\TestQuestion;
use Plugins\ContentBundle\Form\TestQuestionType;
use Plugins\ContentBundle\Entity\Translation\TestQuestionTranslation;

/**
 * TestQuestion controller.
 *
 *
 * @category   PI_CRUD_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class TestQuestionController extends abstractController
{
    protected $_entityName = "PluginsContentBundle:TestQuestion";

    /**
     * Enabled TestQuestion entities.
     *
     * @Route("/admin/gedmo/test_question/enabled", name="admin_gedmo_test_question_enabledentity_ajax")
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
     * Disable TestQuestion entities.
     * 
     * @Route("/admin/gedmo/test_question/disable", name="admin_gedmo_test_question_disablentity_ajax")
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
     * Change the position of a TestQuestion entity.
     *
     * @Route("/admin/gedmo/test_question/position", name="admin_gedmo_test_question_position_ajax")
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
     * Delete a TestQuestion entity.
     *
     * @Route("/admin/gedmo/test_question/delete", name="admin_gedmo_test_question_deletentity_ajax")
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
     * archive TestQuestion entities.
     *
     * @Route("/admin/gedmo/test_question/archive", name="admin_gedmo_test_question_archiventity_ajax")
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
     * Lists all TestQuestion entities.
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
        
    	$id    = $this->container->get('request')->query->get('id');
        $category    = $this->container->get('request')->query->get('category');
        $NoLayout    = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout) 	$template = "index.html.twig"; else $template = "index.html.twig";
        
        if ($id){
    		$query    = $em->getRepository("PluginsContentBundle:TestQuestion")->setContainer($this->container)->getAllByCategory($category, null, '', 'DESC', false);
    		$query
    		->leftJoin("a.test", 't')
    		->andWhere("t.id = {$id}");
    	} else {
    		$query    = $em->getRepository("PluginsContentBundle:TestQuestion")->getAllByCategory($category, null, '', 'ASC', false);
    	}
        $entities    = $em->getRepository("PluginsContentBundle:TestQuestion")->findTranslationsByQuery($locale, $query->getQuery(), 'object', false);
        
        return $this->render("PluginsContentBundle:TestQuestion:$template", array(
            'entities'	=> $entities,
            'NoLayout'	=> $NoLayout,
            'category'	=> $category,
        ));
    }

    /**
     * Finds and displays a TestQuestion entity.
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
        $entity     = $em->getRepository("PluginsContentBundle:TestQuestion")->findOneByEntity($locale, $id, 'object');
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "show.html.twig"; else $template = "show.html.twig";        

        if (!$entity) {
            throw ControllerException::NotFoundException('TestQuestion');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:TestQuestion:$template", array(
            'entity'      => $entity,
            'NoLayout'      => $NoLayout,
            'category'      => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new TestQuestion entity.
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
        $entity     = new TestQuestion();
        $form       = $this->createForm(new TestQuestionType($em, $this->container), $entity, array('show_legend' => false));
        
        $category   = $this->container->get('request')->query->get('category', '');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new.html.twig";   
        
        $entity_cat = $em->getRepository("PiAppGedmoBundle:Category")->find($category);
        if (($entity_cat instanceof \PiApp\GedmoBundle\Entity\Category) && method_exists($entity, 'setCategory'))
            $entity->setCategory($entity_cat);     
        elseif (!empty($category) && method_exists($entity, 'setCategory'))
            $entity->setCategory($category);
            
        return $this->render("PluginsContentBundle:TestQuestion:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Creates a new TestQuestion entity.
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
    
        $entity     = new TestQuestion();
        $request     = $this->getRequest();
        $form        = $this->createForm(new TestQuestionType($em, $this->container), $entity, array('show_legend' => false));
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_content_test_question_show', array('id' => $entity->getId(), 'NoLayout' => $NoLayout, 'category' => $category)));
                        
        }

        return $this->render("PluginsContentBundle:TestQuestion:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
            'category'    => $category,
        ));
    }

    /**
     * Displays a form to edit an existing TestQuestion entity.
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
            $entity    = $em->getRepository("PluginsContentBundle:TestQuestion")->findOneByEntity($locale, $id, 'object');
        } else {
            $slug    = $this->container->get('bootstrap.RouteTranslator.factory')->getMatchParamOfRoute('slug', $locale, true);
            $entity    = $this->container->get('doctrine')->getManager()->getRepository("PluginsContentBundle:TestQuestion")->getEntityByField($locale, array('content_search' => array('slug' =>$slug)), 'object');
        }        
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:TestQuestion")->find($id);
            $entity->addTranslation(new TestQuestionTranslation($locale));            
        }

        $editForm   = $this->createForm(new TestQuestionType($em, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:TestQuestion:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Edits an existing TestQuestion entity.
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
        $entity     = $em->getRepository("PluginsContentBundle:TestQuestion")->findOneByEntity($locale, $id, "object"); 
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "edit.html.twig";  else    $template = "edit.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:TestQuestion")->find($id);
        }

        $editForm   = $this->createForm(new TestQuestionType($em, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($this->getRequest(), $entity);
        if ($editForm->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_content_test_question_edit', array('id' => $id, 'NoLayout' => $NoLayout, 'category' => $category)));
        }

        return $this->render("PluginsContentBundle:TestQuestion:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'category'      => $category,
        ));
    }

    /**
     * Deletes a TestQuestion entity.
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
            $entity = $em->getRepository("PluginsContentBundle:TestQuestion")->findOneByEntity($locale, $id, 'object');

            if (!$entity) {
                throw ControllerException::NotFoundException('TestQuestion');
            }

            try {
                $em->remove($entity);
                $em->flush();
            } catch (\Exception $e) {
                $this->container->get('request')->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
            }
        }

        return $this->redirect($this->generateUrl('admin_content_test_question', array('NoLayout' => $NoLayout, 'category' => $category)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Template : Finds and displays a TestQuestion entity.
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
            
        $entity = $em->getRepository("PluginsContentBundle:TestQuestion")->findOneByEntity($lang, $id, 'object', false);
        
        if (!$entity) {
            throw ControllerException::NotFoundException('TestQuestion');
        }
        
        if (method_exists($entity, "getTemplate") && $entity->getTemplate() != "")
            $template = $entity->getTemplate();         
    
        return $this->render("PluginsContentBundle:TestQuestion:$template", array(
                'entity'    => $entity,
                'locale'    => $lang,
        ));
    }

    /**
     * Template : Finds and displays a list of TestQuestion entity.
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
            
        $query        = $em->getRepository("PluginsContentBundle:TestQuestion")->getAllByCategory($category, $MaxResults, $order)->getQuery();
        $entities   = $em->getRepository("PluginsContentBundle:TestQuestion")->findTranslationsByQuery($lang, $query, 'object', false);                   

        return $this->render("PluginsContentBundle:TestQuestion:$template", array(
            'entities' => $entities,
            'cat'       => ucfirst($category),
            'locale'   => $lang,
        ));
    } 

    /**
     * Template : Finds and displays an archive of TestQuestion entity.
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
    
        $count                 = $em->getRepository("PluginsContentBundle:TestQuestion")->count(1);
        $query_pagination    = $em->getRepository("PluginsContentBundle:TestQuestion")->getAllByCategory('', null, $order)->getQuery();
        $query_pagination->setHint('knp_paginator.count', $count);
         
        $pagination = $paginator->paginate(
                $query_pagination,
                $page,    /*page number*/
                $MaxResults        /*limit per page*/
        );
         
        //print_r($pagination);exit;
         
        $query_pagination->setFirstResult(($page-1)*$MaxResults);
        $query_pagination->setMaxResults($MaxResults);
        $query_pagination    = $em->getRepository("PluginsContentBundle:TestQuestion")->setTranslatableHints($query_pagination, $lang, false);
        $entities            = $em->getRepository("PluginsContentBundle:TestQuestion")->findTranslationsByQuery($lang, $query_pagination, 'object', false);
         
        return $this->render("PluginsContentBundle:TestQuestion:$template", array(
                'entities'        => $entities,
                'pagination'    => $pagination,
                'locale'        => $lang,
                'lang'            => $lang,
        ));        
    }        
    
}