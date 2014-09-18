<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\AdminBundle\Entity\Langue;
use App\AdminBundle\Form\LangueType;

/**
 * Langue controller.
 *
 * @Route("/langue")
 */
class LangueController extends Controller
{

    /**
     * Lists all Langue entities.
     *
     * @Route("/", name="langue")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppAdminBundle:Langue')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Langue entity.
     *
     * @Route("/", name="langue_create")
     * @Method("POST")
     * @Template("AppAdminBundle:Langue:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Langue();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('langue'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Langue entity.
     *
     * @param Langue $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Langue $entity)
    {
        $form = $this->createForm(new LangueType(), $entity, array(
            'action' => $this->generateUrl('langue_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new Langue entity.
     *
     * @Route("/new", name="langue_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Langue();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Langue entity.
     *
     * @Route("/{id}", name="langue_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppAdminBundle:Langue')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Langue entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Langue entity.
     *
     * @Route("/{id}/edit", name="langue_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppAdminBundle:Langue')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Langue entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Langue entity.
    *
    * @param Langue $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Langue $entity)
    {
        $form = $this->createForm(new LangueType(), $entity, array(
            'action' => $this->generateUrl('langue_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    /**
     * Edits an existing Langue entity.
     *
     * @Route("/{id}", name="langue_update")
     * @Method("PUT")
     * @Template("AppAdminBundle:Langue:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppAdminBundle:Langue')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Langue entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('langue'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Langue entity.
     *
     * @Route("/{id}", name="langue_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppAdminBundle:Langue')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Langue entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('langue'));
    }

    /**
     * Creates a form to delete a Langue entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('langue_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * Delete langue entity.
     *
     * @Route("/deletelangue/{id}", name="langue_delete_custom")
     */
    public function deleteLangueAction($id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oLangueSelected = $oEm->getRepository('AppAdminBundle:Langue')->find($id);
        
//        var_dump($oLangueSelected);

        if (!$oLangueSelected) {
            throw $this->createNotFoundException('Unable to find Field entity.');
        }
        
        // recuperation des champs traduits
        $aFieldsTranslated = $oEm->getRepository("AppAdminBundle:FieldTranslation")
                ->findByLangue($oLangueSelected);
        
         foreach ($aFieldsTranslated as $oFieldsTranslated) {
            
            $oEm->remove($oFieldsTranslated);
        } 
        
        // Recuperation des questions traduites.
        $aQuestionTranslation = $oEm->getRepository("AppAdminBundle:QuestionTranslation")
                 ->findByLangues($oLangueSelected);
        
        foreach ($aQuestionTranslation as $oQuestionTranslation) {
            
            $oEm->remove($oQuestionTranslation);
        } 
        
        // TODO lbrau pour adlm : C'est a ce remove que ca pete. Bon courage.
//        var_dump("<PRE>",$oLangueSelected);die;
        $oEm->remove($oLangueSelected);
        $oEm->flush();
        
        return $this->redirect($this->generateUrl('langue'));
    }
}
