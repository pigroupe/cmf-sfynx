<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\AdminBundle\Entity\Croping;
use App\AdminBundle\Form\CropingType;

/**
 * Croping controller.
 *
 * @Route("/croping")
 */
class CropingController extends Controller
{

    /**
     * Lists all Croping entities.
     *
     * @Route("/", name="croping")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppAdminBundle:Croping')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Croping entity.
     *
     * @Route("/", name="croping_create")
     * @Method("POST")
     * @Template("AppAdminBundle:Croping:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Croping();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('croping_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Croping entity.
     *
     * @param Croping $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Croping $entity)
    {
        $form = $this->createForm(new CropingType(), $entity, array(
            'action' => $this->generateUrl('croping_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Croping entity.
     *
     * @Route("/new", name="croping_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Croping();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Croping entity.
     *
     * @Route("/{id}", name="croping_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppAdminBundle:Croping')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Croping entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Croping entity.
     *
     * @Route("/{id}/edit", name="croping_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppAdminBundle:Croping')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Croping entity.');
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
    * Creates a form to edit a Croping entity.
    *
    * @param Croping $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Croping $entity)
    {
        $form = $this->createForm(new CropingType(), $entity, array(
            'action' => $this->generateUrl('croping_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Croping entity.
     *
     * @Route("/{id}", name="croping_update")
     * @Method("PUT")
     * @Template("AppAdminBundle:Croping:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppAdminBundle:Croping')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Croping entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('croping_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Croping entity.
     *
     * @Route("/{id}", name="croping_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppAdminBundle:Croping')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Croping entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('croping'));
    }

    /**
     * Creates a form to delete a Croping entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('croping_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
