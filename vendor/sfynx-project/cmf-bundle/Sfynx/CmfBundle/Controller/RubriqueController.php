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

use Sfynx\CmfBundle\Entity\Rubrique;
use Sfynx\CmfBundle\Form\RubriqueType;

/**
 * Rubrique controller.
 * 
 * @subpackage   Admin_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class RubriqueController extends CmfabstractController
{
    protected $_entityName = "SfynxCmfBundle:Rubrique";
    
    /**
     * Lists all Rubrique entities.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function indexAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        $entities     = $em->getRepository('SfynxCmfBundle:Rubrique')->findAll();

        return $this->render('SfynxCmfBundle:Rubrique:index.html.twig', array(
            'entities' => $entities,
            'NoLayout' => $NoLayout,
        ));
    }
    
    /**
     * Enabled Rubrique entities.
     *
     * @Route("/admin/rubrique/enabled", name="admin_rubrique_enabledentity_ajax")
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
     * Disable Rubrique  entities.
     *
     * @Route("/admin/rubrique/disable", name="admin_rubrique_disablentity_ajax")
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
     * Finds and displays a Rubrique entity.
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
        $entity     = $em->getRepository('SfynxCmfBundle:Rubrique')->find($id);
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');

        if (!$entity) {
            throw ControllerException::NotFoundEntity('Rubrique');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SfynxCmfBundle:Rubrique:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'NoLayout'  => $NoLayout,
        ));
    }

    /**
     * Displays a form to create a new Rubrique entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function newAction()
    {
        $entity = new Rubrique();
        $em     = $this->getDoctrine()->getManager();
        $form   = $this->createForm(new RubriqueType(), $entity, array('show_legend' => false));
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        $parent_id  = $this->container->get('request')->query->get('parent');
        
        if ($parent_id){
            $parent = $em->getRepository("SfynxCmfBundle:Rubrique")->find($parent_id);
            $entity->setParent($parent);
        }

        $form   = $this->createForm(new RubriqueType($em), $entity, array('show_legend' => false));
        return $this->render('SfynxCmfBundle:Rubrique:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'NoLayout'  => $NoLayout,
        ));
    }

    /**
     * Creates a new Rubrique entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function createAction()
    {
        $entity  = new Rubrique();
        $request = $this->getRequest();
        $form    = $this->createForm(new RubriqueType(), $entity, array('show_legend' => false));
        $form->bind($request);
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('admin_rubrique_show', array('id' => $entity->getId(), 'NoLayout' => $NoLayout)));            
        }

        return $this->render('SfynxCmfBundle:Rubrique:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'NoLayout'  => $NoLayout,
        ));
    }

    /**
     * Displays a form to edit an existing Rubrique entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function editAction($id)
    {
        $em         = $this->getDoctrine()->getManager();
        $entity     = $em->getRepository('SfynxCmfBundle:Rubrique')->find($id);
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');        

        if (!$entity) {
            throw ControllerException::NotFoundEntity('Rubrique');
        }

        $editForm     = $this->createForm(new RubriqueType(), $entity, array('show_legend' =>false));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SfynxCmfBundle:Rubrique:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'  => $NoLayout,
        ));
    }

    /**
     * Edits an existing Rubrique entity.
     * 
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function updateAction($id)
    {
        $em         = $this->getDoctrine()->getManager();
        $entity     = $em->getRepository('SfynxCmfBundle:Rubrique')->find($id);
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');

        if (!$entity) {
            throw ControllerException::NotFoundEntity('Rubrique');
        }

        $editForm   = $this->createForm(new RubriqueType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('admin_rubrique_edit', array('id' => $id, 'NoLayout' => $NoLayout)));
        }

        return $this->render('SfynxCmfBundle:Rubrique:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'  => $NoLayout,
        ));
    }

    /**
     * Deletes a Rubrique entity.
     * 
     * @Secure(roles="ROLE_SUPER_ADMIN")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteAction($id)
    {
        $form         = $this->createDeleteForm($id);
        $request     = $this->getRequest();
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');

        $form->bind($request);

        if ($form->isValid()) {
            $em     = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SfynxCmfBundle:Rubrique')->find($id);

            if (!$entity) {
                throw ControllerException::NotFoundEntity('Rubrique');
            }

            try {
                $em->remove($entity);
                $em->flush();
            } catch (\Exception $e) {
                $this->container->get('request')->getSession()->getFlashBag()->add('notice', 'pi.session.flash.right.undelete');
            }            
        }

        return $this->redirect($this->generateUrl('admin_rubrique', array('NoLayout' => $NoLayout)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * Create a tree of the tree
     *
     * @Secure(roles="ROLE_EDITOR")
     * @param string $category
     * 
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function treeAction()
    {
        $em        = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
         
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "tree.html.twig"; else $template = "tree_ajax.html.twig";
    
        // tree management
        $self = &$this;
        $self->NoLayout = $NoLayout;
        $self->translator = $this->container->get('translator');
        $options = array(
                'decorate' => true,
                'rootOpen' => "\n <div class='inner'><ul> \n",
                'rootClose' => "\n </ul></div> \n",
                'childOpen' => "    <li> \n",        // 'childOpen' => "    <li class='collapsed' > \n",
                'childClose' => "    </li> \n",
                'nodeDecorator' => function($node) use (&$self) {                     
                    // define of all url images
                    $Urlpath0     = $self->get('templating.helper.assets')->getUrl('bundles/sfynxtemplate/images/icons/tree/plus.png');
                    $UrlpathAdd   = $self->get('templating.helper.assets')->getUrl('bundles/sfynxtemplate/images/icons/tree/add.png');
                    $Urlpath1     = $self->get('templating.helper.assets')->getUrl('bundles/sfynxtemplate/images/icons/tree/view.png');
                    $Urlpath2     = $self->get('templating.helper.assets')->getUrl('bundles/sfynxtemplate/images/icons/tree/up.png');
                    $Urlpath3     = $self->get('templating.helper.assets')->getUrl('bundles/sfynxtemplate/images/icons/tree/down.png');
                    $Urlpath4     = $self->get('templating.helper.assets')->getUrl('bundles/sfynxtemplate/images/icons/tree/remove.png');
    
                    $linkNode     = '<h4>'. $node['titre'] . '&nbsp;&nbsp;&nbsp; (node: ' .  $node['id'] . ', level : ' .  $node['lvl'] . ')' . '</h4>';
    
                    if ( ($node['lft'] == -1) && ($node['rgt'] == 0) )   $linkNode .= '<div class="inner">';
                    if ( ($node['lft'] !== -1) && ($node['rgt'] !== 0) ) $linkNode .= '<div class="inner">';
                    if ( ($node['lft'] == -1) && ($node['rgt'] !== 0) )  $linkNode .= '<div class="inner">';
    
                    $linkAdd    = '<a href="#" class="tree-action" data-url="' . $self->generateUrl('admin_rubrique_new', array("NoLayout" => true,  'parent' => $node['id'])) . '" ><img src="'.$UrlpathAdd.'" title="'.$self->translator->trans('pi.add').'"  width="16" /></a>';
                    $linkEdit   = '<a href="#" class="tree-action" data-url="' . $self->generateUrl('admin_rubrique_edit', array('id' => $node['id'], "NoLayout" => true)) . '" ><img src="'.$Urlpath1.'" title="'.$self->translator->trans('pi.edit').'"  width="16" /></a>';
                    $linkUp     = '<a href="' . $self->generateUrl('admin_rubrique_move_up', array('id' => $node['id'],  'NoLayout'=> $self->NoLayout)) . '"><img src="'.$Urlpath2.'" title="'.$self->translator->trans('pi.move-up').'" width="16" /></a>';
                    $linkDown   = '<a href="' . $self->generateUrl('admin_rubrique_move_down', array('id' => $node['id'],  'NoLayout'=> $self->NoLayout)) . '"><img src="'.$Urlpath3.'" title="'.$self->translator->trans('pi.move-down').'" width="16" /></a>';
                    $linkDelete = '<a href="' . $self->generateUrl('admin_rubrique_node_remove', array('id' => $node['id'],  'NoLayout'=> $self->NoLayout)) . '"><img src="'.$Urlpath4.'" title="'.$self->translator->trans('pi.delete').'"  width="16" /></a>';
    
                    $linkNode .= $linkAdd . '&nbsp;&nbsp;&nbsp;' . $linkEdit . '&nbsp;&nbsp;&nbsp;' . $linkUp . '&nbsp;&nbsp;&nbsp;' . $linkDown . '&nbsp;&nbsp;&nbsp;' . $linkDelete;
    
                    if ( ($node['lft'] == -1) && ($node['rgt'] == 0) )  $linkNode .= '</div>'; // if ( ($node['lft'] == -1) && ($node['rgt'] !== 0) )
                    if ( ($node['lft'] == -1) && ($node['rgt'] !== 0) ) $linkNode .= '</div>'; // if ( ($node['lft'] == -1) && ($node['rgt'] !== 0) )
                    return $linkNode;
                }
        );
         
        // we repair the tree
        $em->getRepository("SfynxCmfBundle:Rubrique")->recover();
        $result = $em->getRepository("SfynxCmfBundle:Rubrique")->verify();
        
        $node   = $this->container->get('request')->query->get('node');
        if (!empty($node) ){
            $node  = $em->getRepository("SfynxCmfBundle:Rubrique")->findNodeOr404($node, $locale,'object');
        } else {
            $node = null;
        }        
         
        $nodes  = $em->getRepository("SfynxCmfBundle:Rubrique")->getAllTree($locale, '', 'array', false, true, $node);
        $tree   = $em->getRepository("SfynxCmfBundle:Rubrique")->buildTree($nodes, $options);
         
        return $this->render("SfynxCmfBundle:Rubrique:$template", array(
                'tree'          => $tree,
                'NoLayout'      => $NoLayout,
        ));
    }
    
    /**
     * Move the node up in the same level
     *
     * @Secure(roles="ROLE_EDITOR")
     * @param int $id
     * @access    public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function moveUpAction($id)
    {
        $em                 = $this->getDoctrine()->getManager();
        $locale             = $this->container->get('request')->getLocale();
        $NoLayout        = $this->container->get('request')->query->get('NoLayout');
         
        $node             = $em->getRepository("SfynxCmfBundle:Rubrique")->findNodeOr404($id, $locale);
        $entity_node_pos = $node->getRoot();
         
        if ($node->getLvl() == NULL){
            $all_root_nodes     = $em->getRepository("SfynxCmfBundle:Rubrique")->getAllByCategory("", null, "ASC")->getQuery()->getResult();
            foreach($all_root_nodes as $key => $routeNode){
                $routenode_pos = $routeNode->getRoot();
                if ( $routenode_pos < $entity_node_pos ){
                    $em->getRepository("SfynxCmfBundle:Rubrique")->moveRoot($entity_node_pos, -100);
                    $em->getRepository("SfynxCmfBundle:Rubrique")->moveRoot($routenode_pos, $entity_node_pos);
                    $em->getRepository("SfynxCmfBundle:Rubrique")->moveRoot($entity_node_pos, $routenode_pos);
                }
            }
            $em->flush();
        }else
            $em->getRepository("SfynxCmfBundle:Rubrique")->moveUp($node);
    
        // we repair the tree
        $em->getRepository("SfynxCmfBundle:Rubrique")->recover();
        $result = $em->getRepository("SfynxCmfBundle:Rubrique")->verify();
    
        return $this->redirect($this->generateUrl('admin_rubrique_tree', array('NoLayout' => $NoLayout)));
    }
    
    /**
     * Move the node down in the same level
     *
     * @Secure(roles="ROLE_EDITOR")
     * @param int $id
     * @access    public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function moveDownAction($id)
    {
        $em                 = $this->getDoctrine()->getManager();
        $locale             = $this->container->get('request')->getLocale();
        $NoLayout        = $this->container->get('request')->query->get('NoLayout');
         
        $node             = $em->getRepository("SfynxCmfBundle:Rubrique")->findNodeOr404($id, $locale);
        $entity_node_pos = $node->getRoot();
    
        if ($node->getLvl() == NULL){
            $all_root_nodes     = $em->getRepository("SfynxCmfBundle:Rubrique")->getAllByCategory("", null, "DESC")->getQuery()->getResult();
            foreach($all_root_nodes as $key => $routeNode){
                $routenode_pos = $routeNode->getRoot();
                if ( $routenode_pos > $entity_node_pos ){
                    $em->getRepository("SfynxCmfBundle:Rubrique")->moveRoot($entity_node_pos, -100);
                    $em->getRepository("SfynxCmfBundle:Rubrique")->moveRoot($routenode_pos, $entity_node_pos);
                    $em->getRepository("SfynxCmfBundle:Rubrique")->moveRoot($entity_node_pos, $routenode_pos);
                }
            }
            $em->flush();
        }else
            $em->getRepository("SfynxCmfBundle:Rubrique")->moveDown($node);
    
        // we repair the tree
        $em->getRepository("SfynxCmfBundle:Rubrique")->recover();
        $result = $em->getRepository("SfynxCmfBundle:Rubrique")->verify();
         
        return $this->redirect($this->generateUrl('admin_rubrique_tree', array('NoLayout' => $NoLayout)));
    }
    
    /**
     * Removes given $node from the tree and reparents its descendants
     *
     * @Secure(roles="ROLE_EDITOR")
     * @param int $id
     * @access    public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function removeAction($id)
    {
        $em        = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $node    = $em->getRepository("SfynxCmfBundle:Rubrique")->findNodeOr404($id, $locale);
         
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
    
        $em->getRepository("SfynxCmfBundle:Rubrique")->removeFromTree($node);
        return $this->redirect($this->generateUrl('admin_rubrique_tree', array('NoLayout' => $NoLayout)));
    }
        
}
