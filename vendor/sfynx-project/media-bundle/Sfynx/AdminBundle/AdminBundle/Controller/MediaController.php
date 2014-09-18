<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\AdminBundle\Entity\Media;
use App\AdminBundle\Form\MediaType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Media controller.
 *
 * @Route("/media")
 */
class MediaController extends Controller
{

    /**
     * Lists all Media entities.
     *
     * @Route("/", name="media")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sDirPath = 'uploads/documents/';
        $entities = $em->getRepository('AppAdminBundle:Media')->findAll();

        return array(
            'sDirePath' => $sDirPath,
            'entities' => $entities
        );
    }
    
    /**
     * Creates a new Media entity.
     *
     * @Route("/", name="media_create")
     * @Method("POST")
     * @Template("AppAdminBundle:Media:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Media();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $entity->upload();
        $em->persist($entity);
        $em->flush();
        
//        if ($form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($entity);
//            $em->flush();
//
//            return $this->redirect($this->generateUrl('media_show', array('id' => $entity->getId())));
//        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Media entity.
     *
     * @param Media $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Media $entity)
    {
        $form = $this->createForm(new MediaType(), $entity, array(
            'action' => $this->generateUrl('media_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Media entity.
     *
     * @Route("/new", name="media_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Media();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Media entity.
     *
     * @Route("/{id}", name="media_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppAdminBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Media entity.
     *
     * @Route("/{id}/edit", name="media_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppAdminBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $editForm   = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
            
        $formats = $em->getRepository('AppAdminBundle:Croping')->findAll();
        
        // Recuperation des metadonnées de l'image.
        $filename = 'uploads/documents/'.$entity->getPath().'.'.$entity->getExtension();
        $aDataImageOrigin = getimagesize($filename, $infos);
        $iCoeffCrop = $aDataImageOrigin[0] / 500;
        $iImageWidth =  $aDataImageOrigin[0];

        if ( $aDataImageOrigin[0] > 500) {
            
            $iImageWidth = 500;
        }
        
        return array(
            'iImageWith'  => $iImageWidth,
            'dCoeffCrop'  => $iCoeffCrop,
            'entity'      => $entity,
            'formats'     => $formats,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Media entity.
    *
    * @param Media $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Media $entity)
    {
        $form = $this->createForm(new MediaType(), $entity, array(
            'action' => $this->generateUrl('media_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        
//        $form->add('path', 'text', array(
//            'attr' => array (
//                'value' => $entity->getPath()
//            )
//        ));
        
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Media entity.
     *
     * @Route("/{id}", name="media_update")
     * @Method("PUT")
     * @Template("AppAdminBundle:Media:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em     = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppAdminBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        // TODO lbrau : Voir pour gerer l'exception en commentaire au dessous.
        $entity->upload();
        $em->persist($entity);
        $em->flush();

//        if ($editForm->isValid()) {
//            
//            $em->flush();
//
//            return $this->redirect($this->generateUrl('media_edit', array('id' => $id)));
//        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Media entity.
     *
     * @Route("/{id}", name="media_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppAdminBundle:Media')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Media entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('media'));
    }

    /**
     * Creates a form to delete a Media entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('media_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
                'label' => 'Delete',
                'attr'  => array(
                    'class' => 'btn btn-xs btn-danger'
                )))
            ->getForm()
        ;
    }
    
    /**
     * Displays a form to edit an existing Media entity.
     *
     * @Route("/crop", name="media_crop")
     * @Method("POST")
     * @Template()
     */
    public function cropAction(Request $request)
    {     
        if($request->isXmlHttpRequest()) {
//            var_dump("coucou",$request->isXmlHttpRequest());die;
            $em      = $this->getDoctrine()->getManager();
            $x       = $request->get('x');
            $y       = $request->get('y');
            $x2      = $request->get('x2');
            $y2      = $request->get('y2');
            $w       = $request->get('w');
            $h       = $request->get('h');
            $id      = $request->get('id');
            $slug    = $request->get('slug');
            $jpeg_quality = 100;
            
            $entity = $em->getRepository('AppAdminBundle:Media')->find($id);
          
            $src       = $this->get('kernel')->getRootDir() . '/../web/uploads/documents/' . $entity->getPath() . '.' . $entity->getExtension();   
            $extension = pathinfo($src, PATHINFO_EXTENSION);
            $destcrop = $this->get('kernel')->getRootDir() . '/../web/uploads/documents/' . $entity->getPath() . '.' .$entity->getExtension(); 
            $destcrop = explode('.'.$extension, $destcrop);
            $destcrop = $destcrop[0].'_'. $slug . '.' . $extension;
            
            switch ($extension) {
                case 'jpg':
                    $img_r = imagecreatefromjpeg($src);
                    break;
                case 'jpeg':
                    $img_r = imagecreatefromjpeg($src);
                    break;
                case 'gif':
                    $img_r = imagecreatefromgif($src);
                    break;
                case 'png':
                    $img_r = imagecreatefrompng($src);
                    break;
                default:
                    echo "L'image n'est pas dans un format reconnu. Extensions autorisÃ©es : jpg, jpeg, gif, png";
                    break;
            }
            $dst_r = imagecreatetruecolor($w, $h);
            imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $w, $h, $w, $h);
            
            switch ($extension) {
                case 'jpg':
                    imagejpeg($dst_r, $destcrop , $jpeg_quality);
                    break;
                case 'jpeg':
                    imagejpeg($dst_r, $destcrop , $jpeg_quality);
                    break;
                case 'gif':
                    imagegif($dst_r, $destcrop);
                    break;
                case 'png':
                    imagepng($dst_r, $destcrop);
                    break;
                default:
                    echo "L'image n'est pas dans un format reconnu. Extensions autorisÃ©es : jpg, gif, png";
                    break;
            }
            @chmod($destcrop, 0777);
            
            $response = new JsonResponse();
            
            $response->setData(array(
                
                'path' => $entity->getPath()
            ));
            
            return $response;
            //die ('fin');
        }
        
    }
    
}
