<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Controllers
 * @package    Controller
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;

use Sfynx\CmfBundle\Controller\CmfabstractController;
use Sfynx\ToolBundle\Exception\ControllerException;
use Sfynx\CmfBundle\Entity\Translation\TagTranslation;
use Sfynx\CmfBundle\Entity\Tag;
use Sfynx\CmfBundle\Form\TagType;

/**
 * Tag controller.
 * 
 * @subpackage Admin_Controllers
 * @package    Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class TagController extends CmfabstractController
{
    protected $_entityName = "SfynxCmfBundle:Tag";
    
    /**
     * Lists all Tag entities.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function indexAction()
    {
        $em       = $this->getDoctrine()->getManager();
        $locale   = $this->container->get('request')->getLocale();
        $entities = $em->getRepository("SfynxCmfBundle:Tag")->findAllByEntity($locale, 'object');      

        return $this->render('SfynxCmfBundle:Tag:index.html.twig', array(
            'entities' => $entities
        ));
    }
    
    /**
     * Enabled Tag entities.
     *
     * @Route("/admin/tag/enabled", name="admin_tag_enabledentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function enabledajaxAction()
    {
        return parent::enabledajaxAction();
    }
    
    /**
     * Disable Tag  entities.
     *
     * @Route("/admin/tag/disable", name="admin_tag_disablentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function disableajaxAction()
    {
        return parent::disableajaxAction();
    }

    /**
     * get entities in ajax request for select form.
     *
     * @Route("/admin/content/tag/select", name="admin_content_tag_selectentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function selectajaxAction()
    {
    	$request = $this->container->get('request');
    	$em      = $this->getDoctrine()->getManager();
    	$locale  = $this->container->get('request')->getLocale();
    	//
    	$pagination = $this->container->get('request')->get('pagination', null);
    	$keyword    = $this->container->get('request')->get('keyword', '');
    	$MaxResults = $this->container->get('request')->get('max', 10);
    	// we set query
    	$query  = $em->getRepository("SfynxCmfBundle:Tag")
                ->getAllByCategory('', null, '', '', false);
    	$query
    	->leftJoin('a.translations', 'trans');
    	//
        $keyword = array(
    	    0 => array(
    	        'field_name' => 'name',
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
     * @return Response
     * @access  protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function renderselectajaxQuery($entities, $locale)
    {
    	$tab = array();
    	foreach ($entities as $obj) {
            $content   = $obj->getName();
            if (!empty($content)) {
                $tab[] = array(
                    'id'   => $obj->getId(),
                    'text' => $content
                );
            }
    	}
    	 
    	return $tab;
    }    
    	    
    /**
     * Finds and displays a Tag entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function showAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale = $this->container->get('request')->getLocale();
        $entity = $em->getRepository("SfynxCmfBundle:Tag")->findOneByEntity($locale, $id, 'object');        

        if (!$entity) {
            throw ControllerException::NotFoundEntity('Tag');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SfynxCmfBundle:Tag:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Tag entity.
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
        
        $entity = new Tag();
        $form   = $this->createForm(new TagType($em, $locale), $entity, array('show_legend' => false));

        return $this->render('SfynxCmfBundle:Tag:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Tag entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function createAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        
        $entity  = new Tag();
        $request = $this->getRequest();
        $form    = $this->createForm(new TagType($em, $locale), $entity, array('show_legend' => false));
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setTranslatableLocale($locale);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_tag_show', array('id' => $entity->getId())));
        }

        return $this->render('SfynxCmfBundle:Tag:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Tag entity.
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
        $entity = $em->getRepository("SfynxCmfBundle:Tag")->findOneByEntity($locale, $id, 'object');

        if (!$entity) {
            $entity = $em->getRepository("SfynxCmfBundle:Tag")->find($id);
            $entity->addTranslation(new TagTranslation($locale));            
        }

        $editForm     = $this->createForm(new TagType($em, $locale), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SfynxCmfBundle:Tag:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Tag entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function updateAction($id)
    {
        $em        = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $entity = $em->getRepository("SfynxCmfBundle:Tag")->findOneByEntity($locale, $id, 'object');

        if (!$entity) {
            $entity = $em->getRepository("SfynxCmfBundle:Tag")->find($id);
        }

        $editForm   = $this->createForm(new TagType($em, $locale), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($request = $this->getRequest(), $entity);
        if ($editForm->isValid()) {
            $entity->setTranslatableLocale($locale);
            
            $other  = $entity->getGroupnameother();
            if (!empty($other)){
                $entity->setGroupname($other);
                $entity->setGroupnameother('');
                $entity->translate($locale)->setGroupname($other);
                $entity->translate($locale)->setGroupnameother('');
            }          
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_tag_edit', array('id' => $id)));
        }

        return $this->render('SfynxCmfBundle:Tag:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Tag entity.
     * 
     * @Secure(roles="ROLE_SUPER_ADMIN")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SfynxCmfBundle:Tag')->find($id);

            if (!$entity) {
                throw ControllerException::NotFoundEntity('Tag');
            }

            try {
                $em->remove($entity);
                $em->flush();
            } catch (\Exception $e) {
                $this->container->get('request')->getSession()->getFlashBag()->clear();
                $this->container->get('request')->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
            }
        }

        return $this->redirect($this->generateUrl('admin_tag'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
}