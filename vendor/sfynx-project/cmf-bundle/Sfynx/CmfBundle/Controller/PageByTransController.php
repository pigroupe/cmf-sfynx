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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sfynx\CmfBundle\Controller\CmfabstractController;
use Sfynx\ToolBundle\Exception\ControllerException;
use Sfynx\CmfBundle\Entity\Page;
use Sfynx\CmfBundle\Repository\PageRepository;
use Sfynx\CmfBundle\Form\PageByTransType as PageType;

/**
 * PageByTrans controller.
 * 
 * @subpackage Admin_Controllers
 * @package    Controller
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PageByTransController extends CmfabstractController
{
    protected $_entityName = "SfynxCmfBundle:Page";
    
    /**
     * Enabled Page entities.
     *
     * @Route("/admin/pagebytrans/enabled", name="admin_pagebytrans_enabledentity_ajax")
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
     * Disable Page  entities.
     *
     * @Route("/admin/pagebytrans/disable", name="admin_pagebytrans_disablentity_ajax")
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
     * Delete Page entities.
     *
     * @Route("/admin/pagebytrans/delete", name="admin_pagebytrans_deletentity_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteajaxAction()
    {
        return parent::deletajaxAction();
    }  

    /**
     * Delete twig cache Page
     * 
     * @param string $type Type value
     * 
     * @Route("/admin/pagebytrans/deletetwigcache", name="admin_pagebytrans_deletetwigcache_ajax")
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deletetwigcacheajaxAction($type = 'page')
    {
    	return parent::deletetwigcacheajaxAction($type);
    }    
    
    /**
     * Lists all Page entities.
     * 
     * @param string $status Status value
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function wizardAction($status)
    {
        $locale    = $this->container->get('request')->getLocale();
        $em        = $this->getDoctrine()->getManager();
        $token     = $this->get('security.context')->getToken();
        $idUser    = $token->getUser()->getId();
        $RolesUser = $token->getUser()->getRoles();
        if (in_array('ROLE_ADMIN', $RolesUser) 
                || in_array('ROLE_SUPER_ADMIN', $RolesUser) 
                || in_array('ROLE_CONTENT_MANAGER', $RolesUser)
        ) {
            if ($status != "all")
                $entities = $em->getRepository('SfynxCmfBundle:Page')
                    ->getAllPageByStatus($locale, $status)
                    ->getQuery()
                    ->getResult();
            else
                $entities = $em->getRepository('SfynxCmfBundle:Page')
                    ->getAllPageHtml()
                    ->getQuery()
                    ->getResult();
        } else {
            if ($status != "all") {
                $entities = $em->getRepository('SfynxCmfBundle:Page')
                        ->getAllPageByStatus($locale, $status, $idUser)
                        ->getQuery()
                        ->getResult();
            } else {
                $entities = $em->getRepository('SfynxCmfBundle:Page')
                        ->getAllPageHtml($idUser)
                        ->getQuery()
                        ->getResult();
            }
        }
    
        return $this->render('SfynxCmfBundle:PageByTrans:wizard.html.twig', array(
                'entities' => $entities,
                'id_grid'  => 'grid_' . $status,
        ));
    }
    
    /**
     * Lists all Page entities.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function indexAction()
    {
        $em       = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('SfynxCmfBundle:Page')->getAllPageHtml()->getQuery()->getResult();
        
        return $this->render('SfynxCmfBundle:PageByTrans:index.html.twig', array(
            'entities' => $entities
        ));
    }    

    /**
     * Finds and displays a Page entity.
     * 
     * @param integer $id Id value
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function showAction($id)
    {
        $em       = $this->getDoctrine()->getManager();
        $entity   = $em->getRepository('SfynxCmfBundle:Page')->find($id);
        $NoLayout = $this->container->get('request')->query->get('NoLayout');
        if (!$entity) {
            throw ControllerException::NotFoundEntity('Page');
        }
        $deleteForm = $this->createDeleteForm($id);

        return $this->render("SfynxCmfBundle:PageByTrans:show.html.twig", array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'NoLayout'    => $NoLayout,                
        ));
    }

    /**
     * Displays a form to create a new Page entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function newAction()
    {
        $locale = $this->container->get('request')->getLocale();
        $User   = $this->get('security.context')->getToken()->getUser();
        
        $entity = new Page();
        $entity->setMetaContentType(PageRepository::TYPE_TEXT_HTML);
        $entity->setUser($User);
        
        $form   = $this->createForm(new PageType($this->container, $locale, $User->getRoles()), $entity, array('show_legend' => false));
        $NoLayout = $this->container->get('request')->query->get('NoLayout');
        
        //$form->remove('page_css');
        //$form->remove('page_js');
        
        return $this->render("SfynxCmfBundle:PageByTrans:new.html.twig", array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'NoLayout' => $NoLayout,                
        ));
    }

    /**
     * Creates a new Page entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function createAction()
    {
        $NoLayout = $this->container->get('request')->query->get('NoLayout');
        $locale   = $this->container->get('request')->getLocale();
        $User     = $this->get('security.context')->getToken()->getUser();
        
        $entity   = new Page();
        $entity->setMetaContentType(PageRepository::TYPE_TEXT_HTML);
        $entity->setUser($User);
        
        $request = $this->getRequest();
        $form    = $this->createForm(new PageType($this->container, $locale, $User->getRoles()), $entity, array('show_legend' => false));
        $form->bind($request);
        
        if ('POST' === $request->getMethod()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();    
                // We persist all page translations
                foreach($entity->getTranslations() as $translationPage) {
                    $entity->addTranslation($translationPage);
                }                
                $em->persist($entity);
                $em->flush();
    
                return $this->redirect($this->generateUrl('admin_pagebytrans_show', array('id' => $entity->getId(), 'NoLayout' => $NoLayout)));                
            }
    
            return $this->render("SfynxCmfBundle:PageByTrans:new.html.twig", array(
                'entity' => $entity,
                'form'   => $form->createView(),
                'NoLayout' => $NoLayout,                    
            ));
        }
        
        return array('form' => $form->createView());
    }

    /**
     * Displays a form to edit an existing Page entity.
     * 
     * @param Request $request The request instance
     * @param Page    $entity  A page entity
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function editAction(Request $request, Page $entity)
    {
        $locale = $this->container->get('request')->getLocale();
        $User   = $this->get('security.context')->getToken()->getUser();
        $NoLayout = $this->container->get('request')->query->get('NoLayout');
        
        //$this->get('pi_app_admin.form.page.type')->setInit($this->container, $locale, $User->getRoles());
        //$form = $this->get('pi_app_admin.form.pagebytrans');
        
        $editForm = $this->createForm(new PageType($this->container, $locale, $User->getRoles()), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($entity->getId());
        
        return $this->render("SfynxCmfBundle:PageByTrans:edit.html.twig", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'    => $NoLayout,                
        ));
    }

    /**
     * Edits an existing Page entity.
     * 
     * @param Request $request The request instance
     * @param Page    $entity  A page entity
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return Response
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function updateAction(Request $request, Page $entity)
    {
        $locale   = $this->container->get('request')->getLocale();
        $User     = $this->get('security.context')->getToken()->getUser();
        $NoLayout = $this->container->get('request')->query->get('NoLayout');
        if ($this->container->get('security.context')->isGranted("ROLE_SUPER_ADMIN")) {
            $originalTranslations = array();
            // Create an array of the current Widget objects in the database
            foreach ($entity->getTranslations() as $Translation) {
                $originalTranslations[] = $Translation;
            }
        }        
        $editForm   = $this->createForm(new PageType($this->container, $locale, $User->getRoles()), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($entity->getId());
        $editForm->bind($this->getRequest());        
        if ($editForm->isValid()) {
            $em       = $this->getDoctrine()->getManager(); 
            if ($this->container->get('security.context')->isGranted("ROLE_SUPER_ADMIN")) {
                // filter $originalWidgets to contain tags no longer present
                foreach ($entity->getTranslations() as $Translation) {
                    foreach ($originalTranslations as $key => $toDel) {
                        if ($toDel->getId() === $Translation->getId()) {
                            unset($originalTranslations[$key]);
                        }
                    }
                }
                // remove the relationship between the Translation and the page
                foreach ($originalTranslations as $Translation) {
                    $Translation->setPage(null);
                    $em->remove($Translation);
                }
            }            
            // We persist all page translations
            foreach($entity->getTranslations() as $translationPage) {
                $entity->addTranslation($translationPage);
            }
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('admin_pagebytrans_edit', array('id' => $entity->getId(), 'NoLayout' => $NoLayout)));
        }

        return $this->render("SfynxCmfBundle:PageByTrans:edit.html.twig", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'    => $NoLayout,                
        ));
    }

    /**
     * Deletes a Page entity.
     * 
     * @param Request $request The request instance
     * @param Page    $entity  A page entity
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return RedirectResponse
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteAction(Request $request, Page $entity)
    {
        $form = $this->createDeleteForm($entity->getId());
        $request = $this->getRequest();
        $form->bind($request);
        if ($form->isValid()) {
            $em   = $this->getDoctrine()->getManager();  
            try {
                $em->remove($entity);
                $em->flush();
            } catch (\Exception $e) {
                $this->container->get('request')->getSession()->getFlashBag()->clear();
                $this->container->get('request')->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
            }            
        }

        return $this->redirect($this->generateUrl('admin_pagebytrans'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
