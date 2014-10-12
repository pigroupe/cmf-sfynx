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

use Sfynx\CmfBundle\Entity\Page;
use Sfynx\CmfBundle\Repository\PageRepository;
use Sfynx\CmfBundle\Form\PageByBlockType as PageType;

/**
 * PageByBlock controller.
 * 
 * @subpackage   Admin_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PageByBlockController extends CmfabstractController
{
    protected $_entityName = "SfynxCmfBundle:Page";
    
    /**
     * Lists all Page entities.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SfynxCmfBundle:Page')->getAllPageHtml()->getQuery()->getResult();

        return $this->render('SfynxCmfBundle:PageByBlock:index.html.twig', array(
            'entities' => $entities
        ));
    }
    
    /**
     * Enabled Page entities.
     *
     * @Route("/admin/pagebyblock/enabled", name="admin_pagebyblock_enabledentity_ajax")
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
     * Disable Page  entities.
     *
     * @Route("/admin/pagebyblock/disable", name="admin_pagebyblock_disablentity_ajax")
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
     * Finds and displays a Page entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function showAction($id)
    {
        $em         = $this->getDoctrine()->getManager();
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');

        $entity = $em->getRepository('SfynxCmfBundle:Page')->find($id);

        if (!$entity) {
            throw ControllerException::NotFoundEntity('Page');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SfynxCmfBundle:PageByBlock:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
        ));
    }

    /**
     * Displays a form to create a new Page entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function newAction()
    {
        $User   = $this->get('security.context')->getToken()->getUser();
        $entity = new Page();
        $entity->setMetaContentType(PageRepository::TYPE_TEXT_HTML);
        $entity->setUser($User);
        $form   = $this->createForm(new PageType($this->container, $User->getRoles()), $entity, array('show_legend' => false));
        
        //$form->remove('page_css');
        //$form->remove('page_js');
        
        return $this->render('SfynxCmfBundle:PageByBlock:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Page entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function createAction()
    {
        $User      = $this->get('security.context')->getToken()->getUser();
        $entity  = new Page();
        $entity->setMetaContentType(PageRepository::TYPE_TEXT_HTML);
        $entity->setUser($User);
        $request = $this->getRequest();
        $form    = $this->createForm(new PageType($this->container, $User->getRoles()), $entity, array('show_legend' => false));
        $form->bind($request);        

        if ('POST' === $request->getMethod()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                // On persiste tous les blocks d'une page.
                foreach($entity->getBlocks() as $block) {
                    $entity->addBlock($block);
                }
                $em->persist($entity);
                $em->flush();
    
                return $this->redirect($this->generateUrl('admin_pagebyblock_show', array('id' => $entity->getId())));
                
            }
    
            return $this->render('SfynxCmfBundle:PageByBlock:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView()
            ));
        }
        
        return array('form' => $form->createView());
    }

    /**
     * Displays a form to edit an existing Page entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function editAction($id)
    {
        $User     = $this->get('security.context')->getToken()->getUser();
        $em     = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SfynxCmfBundle:Page')->find($id);

        if (!$entity) {
            throw ControllerException::NotFoundEntity('Page');
        }

        $editForm = $this->createForm(new PageType($this->container, $User->getRoles()), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);
        
        return $this->render('SfynxCmfBundle:PageByBlock:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Page entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function updateAction($id)
    {
        $User     = $this->get('security.context')->getToken()->getUser();
        $em     = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SfynxCmfBundle:Page')->find($id);

        if (!$entity) {
            throw ControllerException::NotFoundEntity('Page');
        }

        $editForm   = $this->createForm(new PageType($this->container, $User->getRoles()), $entity, array('show_legend' => false));
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            // On persiste tous les blocks d'une page.
            foreach($entity->getBlocks() as $block) {
                $entity->addBlock($block);
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_pagebyblock_edit', array('id' => $id)));
        }

        return $this->render('SfynxCmfBundle:PageByBlock:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Page entity.
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
            $entity = $em->getRepository('SfynxCmfBundle:Page')->find($id);

            if (!$entity) {
                throw ControllerException::NotFoundEntity('Page');
            }

            $em->remove($entity);
            $em->flush();
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
