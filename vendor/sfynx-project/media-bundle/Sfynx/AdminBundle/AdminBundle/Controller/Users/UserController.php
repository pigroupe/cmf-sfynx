<?php

namespace App\AdminBundle\Controller\Users;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\AdminBundle\Entity\User;
use App\AdminBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/user", name="user_list")
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Route("/", name="user_list")
     * @Method({"GET", "PUT"})
     * @Template("FOSUserBundle:User:index.html.twig")
     */
    public function indexAction()
    {
       //var_dump($this->getRequest());
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppAdminBundle:User')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new User entity.
     *
     * @Route("/", name="user_create")
     * @Method("POST")
     * @Template("AppAdminBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
     
        $data = $request->get($form->getName(), array());
        /*   $user  = $em->getRepository('FOSUserBundle:User')->findOneBy(array('email' => $data["email"]));
        if ($user) {
           var_dump('HELL');
            $form->get('email')->addError(new FormError('Cet email est déjà existant'));
        }

        $user  = $em->getRepository('FOSUserBundle:User')->findOneBy(array('username' => $data["username"]));
        if ($user) {
            $form->get('username')->addError(new FormError('Ce nom d\'utilisateur est déjà existant'));
        }
*/
        if ($form->isValid()) {
            $entity->setUsernameCanonical($data["username"]);
            $entity->setEmailCanonical($data["email"]);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_list'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Creer',
            'attr' => array(
                'class' => 'btn btn-danger'
            ), 
        ));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="user_new")
     * @Method("GET")
     * @Template("FOSUserBundle:User:new.html.twig")
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);
        
        return array(
            'entity' => $entity,
            'roles' => $entity->getRoles(),
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="user_show2")
     * @Method("GET")
     * @Template("AppAdminBundle:User:show.html.twig")
     */
    public function showAction($id = 1)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('AppAdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET","POST"})
     * @Template("AppAdminBundle:User:edit.html.twig")
     */
    public function editAction($id)
    {
       $em = $this->getDoctrine()->getManager();
       $oRequest = $this->getRequest();
       
       if ($oRequest->isMethod('p')) {
          echo "post<br>";
       }
       
        $entity = $em->getRepository('AppAdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'roles' => $entity->getRoles(),
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Modifier',
            'attr' => array(
                'class' => 'btn btn-danger'
            ), 
        ));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="user_update")
     * @Method("PUT")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppAdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('user_list'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppAdminBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user_list'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
                'label' => 'Delete',
                'attr'  => array(
                    'class' => 'btn btn-xs btn-danger'
                )
            ))
            ->getForm()
        ;
    }

    /**
     * Delete UserHermes entity from list
     *
     * @Route("/delete/{iIdUser}/", name="delete_user_from_list")
     */
    public function deleteQuestionListAction($iIdUser)
    {
        $oEm     = $this->getDoctrine()->getManager();
        $oHermesUser  = $oEm->getRepository('AppAdminBundle:User')->find($iIdUser);

        if (!$oHermesUser) {

            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $oEm->remove($oHermesUser);
        $oEm->flush();

        return $this->redirect($this->generateUrl('user_list'));
    }
    
    
    
}
