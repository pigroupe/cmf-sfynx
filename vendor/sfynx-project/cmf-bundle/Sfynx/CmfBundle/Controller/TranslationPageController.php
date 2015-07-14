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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sfynx\CmfBundle\Controller\CmfabstractController;
use Sfynx\ToolBundle\Exception\ControllerException;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Sfynx\CmfBundle\Entity\TranslationPage;
use Sfynx\CmfBundle\Form\TranslationPageType;

/**
 * TranslationPage controller.
 * 
 * @subpackage   Admin_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class TranslationPageController extends CmfabstractController
{
    protected $_entityName = "SfynxCmfBundle:TranslationPage";
    
    /**
     * Lists all TranslationPage entities.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        if (is_null($page))
            $entities = $em->getRepository('SfynxCmfBundle:TranslationPage')->findAll();
        else
            $entities = $em->getRepository('SfynxCmfBundle:TranslationPage')->findBy(array('page'=>$page));        

        return $this->render('SfynxCmfBundle:TranslationPage:index.html.twig', array(
            'entities' => $entities
        ));
    }
    
    /**
     * Enabled TranslationPage entities.
     *
     * @Route("/admin/translationpage/enabled", name="admin_translationpage_enabledentity_ajax")
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
     * Disable TranslationPage  entities.
     *
     * @Route("/admin/translationpage/disable", name="admin_translationpage_disablentity_ajax")
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
     * Finds and displays a TranslationPage entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SfynxCmfBundle:TranslationPage')->find($id);

        if (!$entity) {
            throw ControllerException::NotFoundEntity('TranslationPage');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SfynxCmfBundle:TranslationPage:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new TranslationPage entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function newAction()
    {
        $entity = new TranslationPage();
        $locale    = $this->container->get('request')->getLocale();
        $form   = $this->createForm(new TranslationPageType($locale, $this->container), $entity, array('show_legend' => false));

        return $this->render('SfynxCmfBundle:TranslationPage:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new TranslationPage entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function createAction()
    {
        $locale     = $this->container->get('request')->getLocale();
        $entity  = new TranslationPage();
        $request = $this->getRequest();
        $form    = $this->createForm(new TranslationPageType($locale, $this->container), $entity, array('show_legend' => false));
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_transpage_show', array('id' => $entity->getId())));
            
        }

        return $this->render('SfynxCmfBundle:TranslationPage:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing TranslationPage entity.
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
        $entity = $em->getRepository('SfynxCmfBundle:TranslationPage')->find($id);

        if (!$entity) {
            throw ControllerException::NotFoundEntity('TranslationPage');
        }

        $editForm     = $this->createForm(new TranslationPageType($locale, $this->container), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SfynxCmfBundle:TranslationPage:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing TranslationPage entity.
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
        $locale    = $this->container->get('request')->getLocale();
        $entity = $em->getRepository('SfynxCmfBundle:TranslationPage')->find($id);

        if (!$entity) {
            throw ControllerException::NotFoundEntity('TranslationPage');
        }

        $editForm   = $this->createForm(new TranslationPageType($locale, $this->container), $entity);
        $deleteForm = $this->createDeleteForm($id);
        $request = $this->getRequest();
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_transpage_edit', array('id' => $id)));
        }

        return $this->render('SfynxCmfBundle:TranslationPage:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a TranslationPage entity.
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
            $entity = $em->getRepository('SfynxCmfBundle:TranslationPage')->find($id);

            if (!$entity) {
                throw ControllerException::NotFoundEntity('TranslationPage');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_transpage'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
