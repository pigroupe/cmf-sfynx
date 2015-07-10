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
namespace Cmf\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sfynx\CoreBundle\Controller\abstractController;
use Sfynx\ToolBundle\Exception\ControllerException;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormError;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Cmf\ContentBundle\Entity\Rub;
use Cmf\ContentBundle\Form\RubType;
use Cmf\ContentBundle\Form\RubFavoriteType;
use PiApp\GedmoBundle\Form\CategorySearchForm;
use Cmf\ContentBundle\Entity\Translation\RubTranslation;

/**
 * Rub controller.
 *
 *
 * @category   PI_CRUD_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class RubController extends abstractController
{
    protected $_entityName = "PluginsContentBundle:Rub";

    /**
     * Enabled Rub entities.
     *
     * @Route("/admin/content/rub/enabled", name="admin_content_rub_enabledentity_ajax")
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
     * Disable Rub entities.
     * 
     * @Route("/admin/content/rub/disable", name="admin_content_rub_disablentity_ajax")
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
     * Change the position of a Rub entity.
     *
     * @Route("/admin/content/rub/position", name="admin_content_rub_position_ajax")
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
     * Delete a Rub entity.
     *
     * @Route("/admin/content/rub/delete", name="admin_content_rub_deletentity_ajax")
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
     * archive Rub entities.
     *
     * @Route("/admin/content/rub/archive", name="admin_content_rub_archiventity_ajax")
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
     * Lists all Rub entities.
     *
     * @Secure(roles="IS_AUTHENTICATED_ANONYMOUSLY")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function indexAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $locale        = $this->container->get('request')->getLocale();
        $entities     = $em->getRepository("PluginsContentBundle:Rub")->getAllTree($locale);
         
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "index.html.twig"; else $template = "index_ajax.html.twig";
         
        return $this->render("PluginsContentBundle:Rub:$template", array(
                'entities'    => $entities,
                'NoLayout'      => $NoLayout,
        ));
    }
    
    /**
     * Finds and displays a Rub entity.
     *
     * @Secure(roles="IS_AUTHENTICATED_ANONYMOUSLY")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function showAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $entity = $em->getRepository("PluginsContentBundle:Rub")->findNodeOr404($id, $locale);
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)
            $template = "show.html.twig";
        else
            $template = "show_ajax.html.twig";        
        
        if (!$entity) {
            throw ControllerException::NotFoundException('Rub');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:Rub:$template", array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
        ));
    }

    /**
     * Displays a form to create a new Rub entity.
     *
     * @Secure(roles="ROLE_USER")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function newAction()
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $entity = new Rub();
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new_ajax.html.twig";    

        $category   = $this->container->get('request')->query->get('category');
        if ($category)
            $entity->setCategory($em->getRepository("PiAppGedmoBundle:Category")->find($category));
        
        $parent_id   = $this->container->get('request')->query->get('parent');
        if ($parent_id){
            $parent = $em->getRepository("PluginsContentBundle:Rub")->findNodeOr404($parent_id, $locale);
            $entity->setParent($parent);
        }
        
        $form   = $this->createForm(new RubType($this->container, $em), $entity, array('show_legend' => false));        
        return $this->render("PluginsContentBundle:Rub:$template", array(
            'entity'     => $entity,
            'form'       => $form->createView(),
            'NoLayout'  => $NoLayout,
        ));
    }

    /**
     * Creates a new Rub entity.
     *
     * @Secure(roles="ROLE_USER")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>     
     */
    public function createAction()
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        
        $category   = $this->container->get('request')->query->get('category');
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)    $template = "new.html.twig";  else     $template = "new_ajax.html.twig";        
    
        $entity  = new Rub();
        $request = $this->getRequest();
        $form    = $this->createForm(new RubType($this->container, $em), $entity, array('show_legend' => false));
        $form->bind($request);
        $data = $this->getRequest()->request->get($form->getName(), array());
        $query	= $em->getRepository("PluginsContentBundle:Rub")->createQueryBuilder('a')->select('a');

        $query->leftJoin('a.translations', 'trans');
        $andModule_title = $query->expr()->andx();
        $andModule_title->add($query->expr()->eq('LOWER(trans.locale)', "'{$locale}'"));
        $andModule_title->add($query->expr()->eq('LOWER(trans.field)', "'title'"));
        $andModule_title->add($query->expr()->like('LOWER(trans.content)', $query->expr()->literal($data["title"])));
        $query->where($andModule_title);
        
        $entities          = $em->getRepository("PluginsContentBundle:Rub")->findTranslationsByQuery($locale, $query->getQuery(), 'object', false);	
        $count             = count($entities);
        if ($count > 0) {
            $form->get('title')->addError(new FormError('La rubrique existe déjà'));
        }
        if ($form->isValid()) {
                $entity->setTranslatableLocale($locale);
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('admin_content_rub_show', array('id' => $entity->getId(), 'NoLayout' => $NoLayout)));
        }

        return $this->render("PluginsContentBundle:Rub:$template", array(
            'entity' => $entity,
            'form'   => $form->createView(),
               'NoLayout'  => $NoLayout,
        ));
    }

    /**
     * Displays a form to edit an existing Rub entity.
     *
     * @Secure(roles="ROLE_USER")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function editAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        
        if(!empty($id)){
    		$entity	= $em->getRepository("PluginsContentBundle:Rub")->findNodeOr404($id, $locale, 'object');
    	}else{
    		$slug	= $this->container->get('bootstrap.RouteTranslator.factory')->getMatchParamOfRoute('rubrique', $locale, true);
            $entity	= $this->container->get('doctrine')->getManager()->getRepository("PluginsContentBundle:Rub")->getEntityByField($locale, array('content_search' => array('slug' =>$slug)), 'object');
            $id =$entity->getId();
        }
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)
            $template = "edit.html.twig";
        else
            $template = "edit_ajax.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:Rub")->find($id);
            $entity->addTranslation(new RubTranslation($locale));            
        }
        
        $favorite   = $this->container->get('request')->query->get('favorite');
        if($favorite){
            $editForm   = $this->createForm(new RubFavoriteType($this->container, $em), $entity, array('show_legend' => false));
        }else{
            $editForm   = $this->createForm(new RubType($this->container, $em), $entity, array('show_legend' => false));
        }
        $deleteForm = $this->createDeleteForm($id);

        return $this->render("PluginsContentBundle:Rub:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'       => $NoLayout,
            'favorite'    => $favorite,
        ));
    }

    /**
     * Edits an existing Rub entity.
     *
     * @Secure(roles="ROLE_USER")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>   
     */
    public function updateAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $entity = $em->getRepository("PluginsContentBundle:Rub")->findNodeOr404($id, $locale, "object");
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)
            $template = "edit.html.twig";
        else
            $template = "edit_ajax.html.twig";        

        if (!$entity) {
            $entity = $em->getRepository("PluginsContentBundle:Rub")->find($id);
        }
        
        $favorite   = $this->container->get('request')->query->get('favorite');
        if($favorite){
            $editForm   = $this->createForm(new RubFavoriteType($this->container, $em), $entity, array('show_legend' => false));
        }else{
            $editForm   = $this->createForm(new RubType($this->container, $em), $entity, array('show_legend' => false));
        }

        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($this->getRequest(), $entity);
        
        $data = $this->getRequest()->request->get($editForm->getName(), array());

        if(isset($data["title"])){
            $query	= $em->getRepository("PluginsContentBundle:Rub")->createQueryBuilder('a')->select('a');
            $query->leftJoin('a.translations', 'trans');
            $query->where("a.id != {$id}");
            $andModule_title = $query->expr()->andx();
            $andModule_title->add($query->expr()->eq('LOWER(trans.locale)', "'{$locale}'"));
            $andModule_title->add($query->expr()->eq('LOWER(trans.field)', "'title'"));
            $andModule_title->add($query->expr()->like('LOWER(trans.content)', $query->expr()->literal($data["title"])));
            $query->andWhere($andModule_title);

            $entities          = $em->getRepository("PluginsContentBundle:Rub")->findTranslationsByQuery($locale, $query->getQuery(), 'object', false);
            $count             = count($entities);

            if ($count > 0) {
                $editForm->get('title')->addError(new FormError('La rubrique existe déjà'));
            }
        }


        if(empty($data["media"]) && ($data['parent'] == 37)){
            $editForm->get('media')->addError(new FormError('L\'image est obligatoire'));
        }

        if ($editForm->isValid()) {
                $entity->setTranslatableLocale($locale);
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('admin_content_rub_edit', array('id' => $id, 'NoLayout' => $NoLayout, 'favorite' => $favorite)));
        }

        return $this->render("PluginsContentBundle:Rub:$template", array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'NoLayout'    => $NoLayout,
            'favorite'    => $favorite,
        ));
    }

    /**
     * Deletes a Rub entity.
     *
     * @Secure(roles="ROLE_USER")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>     
     */
    public function deleteAction($id)
    {
        $em      = $this->getDoctrine()->getManager();
        $locale     = $this->container->get('request')->getLocale();
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
    
        $form      = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $entity = $em->getRepository("PluginsContentBundle:Rub")->findNodeOr404($id, $locale, 'object');

            if (!$entity) {
                throw ControllerException::NotFoundException('Rub');
            }

            try {
                    $em->remove($entity);
                    $em->flush();
            } catch (\Exception $e) {
                $this->container->get('request')->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
            }
        }

        return $this->redirect($this->generateUrl('admin_content_rub', array('NoLayout' => $NoLayout)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }    
    
    /**
     * @Secure(roles="ROLE_USER")
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function knpAction()
    {
        $em            = $this->getDoctrine()->getManager();
        $query         = $em->getRepository("PluginsContentBundle:Rub")->findAllByEntity($locale, 'object');
    
        $paginator    = $this->get('knp_paginator');
        $categories = $paginator->paginate(
                $query,
                $this->get('request')->query->get('page', 1),
                10
        );
        return $this->render('PluginsContentBundle:Rub:knp.html.twig', array(
                'categories'     => $categories,
        ));    
    }
    
    /**
     * Create a tree of the tree
     * 
     * @Secure(roles="ROLE_USER")
     * @param string $category
     * @access    public
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function treeAction($category)
    {
        $em        = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "tree.html.twig"; else $template = "tree_ajax.html.twig";
        
        // tree management
        $self = &$this;
        $self->locale = $locale;
        $self->em = $em;
        $self->category = $category;
        $self->NoLayout = $NoLayout;
        $self->translator = $this->container->get('translator');
        $options = array(
                'decorate' => true,
                'rootOpen' => "\n <div class='inner'><ul> \n",
                'rootClose' => "\n </ul></div> \n",
                'childOpen' => "    <li> \n",        // 'childOpen' => "    <li class='collapsed' > \n",
                'childClose' => "    </li> \n",
                'nodeDecorator' => function($node) use (&$self) {
                    $tree   = $self->getContainer()->get('doctrine')->getManager()->getRepository($self->_entityName)->findOneById($node['id']);
                    // we set the resulmt to the $lang language value
                    $tree->setTranslatableLocale($self->locale);
                    $self->em->refresh($tree);        
                    
                    // define of all url images
                    $Urlpath0     = $self->get('templating.helper.assets')->getUrl('bundles/piappadmin/images/icons/tree/plus.png');
                    $UrlpathAdd    = $self->get('templating.helper.assets')->getUrl('bundles/piappadmin/images/icons/tree/add.png');
                    $Urlpath1     = $self->get('templating.helper.assets')->getUrl('bundles/piappadmin/images/icons/tree/view.png');
                    $Urlpath2     = $self->get('templating.helper.assets')->getUrl('bundles/piappadmin/images/icons/tree/up.png');
                    $Urlpath3     = $self->get('templating.helper.assets')->getUrl('bundles/piappadmin/images/icons/tree/down.png');
                    $Urlpath4     = $self->get('templating.helper.assets')->getUrl('bundles/piappadmin/images/icons/tree/remove.png');
                    $Urlpath5     = $self->get('templating.helper.assets')->getUrl('bundles/piappadmin/images/icons/tree/favoris.png');
                    
                    $title = $tree->getTitle();
                    $title = str_replace(array('<br>', '<br/>'), array(' ', ' '), $title);
                    $title = preg_replace('/([ \t\r\n\v\f])(\d{0,3})([ \t\r\n\v\f])/i', '  ', $title);
                    $title = preg_replace("/<[bB]{1}[rR]{1}[ ]*[\/]*>/xsm", '', $title);
                    
                    $enabled= '';
                    if(!$node['enabled']){
                        $enabled ='&nbsp;&nbsp;&nbsp; [archivé]';
                    }
                    $linkNode     = '<h4>'. $title .$enabled . '&nbsp;&nbsp;&nbsp; (node: ' .  $node['id'] . ', level : ' .  $node['lvl'] . ')' . '</h4>';
                    
                    if ( ($node['lft'] == -1) && ($node['rgt'] == 0) )   $linkNode .= '<div class="inner">';
                    if ( ($node['lft'] !== -1) && ($node['rgt'] !== 0) ) $linkNode .= '<div class="inner">';
                    if ( ($node['lft'] == -1) && ($node['rgt'] !== 0) )  $linkNode .= '<div class="inner">';
                    if($node['lvl'] < 2 )                   
                        $linkAdd    = '<a href="#" class="tree-action" data-url="' . $self->generateUrl('admin_content_rub_new', array("NoLayout" => true, 'category'=>$self->category, 'parent' => $node['id'])) . '" ><img src="'.$UrlpathAdd.'" title="'.$self->translator->trans('pi.add').'"  width="16" /></a>';
                    else
                        $linkAdd    = '';   

                    if($node['lvl'] == 1) {
                    	$rub_url = $self->container->get('bootstrap.RouteTranslator.factory')->getRoute("page_rubrique", array("locale"=>$self->locale, "rubrique"=>$tree->getSlug()));
	                    $linkRubFront = "<a href='{$rub_url}' target='_blank'> >> Lien front</a>";
                    } else {
                    	if ($tree->getParent() instanceof \Cmf\ContentBundle\Entity\Rub) {
                    		$rub_url = $self->container->get('bootstrap.RouteTranslator.factory')->getRoute("page_sous_rubrique", array("locale"=>$self->locale, "rubrique"=>$tree->getParent()->getSlug(), 'sousrubrique'=>$tree->getSlug()));
                   		} else {
                   			$rub_url = $self->container->get('bootstrap.RouteTranslator.factory')->getRoute("page_rubrique", array("locale"=>$self->locale, "rubrique"=>$tree->getSlug()));
                   		}
                    	$linkRubFront = "<a href='{$rub_url}' target='_blank'> >> Lien front</a>";
                    }
                    
                    $linkEdit   = '<a href="#" class="tree-action" data-url="' . $self->generateUrl('admin_content_rub_edit', array('id' => $node['id'], "NoLayout" => true)) . '" ><img src="'.$Urlpath1.'" title="'.$self->translator->trans('pi.edit').'"  width="16" /></a>';
                    $linkUp        = '<a class="linkUp" href="' . $self->generateUrl('admin_content_rub_move_up', array('id' => $node['id'], 'category'=>$self->category, 'NoLayout'=> $self->NoLayout)) . '"><img src="'.$Urlpath2.'" title="'.$self->translator->trans('pi.move-up').'" width="16" /></a>';
                    $linkDown     = '<a class="linkDown" href="' . $self->generateUrl('admin_content_rub_move_down', array('id' => $node['id'], 'category'=>$self->category, 'NoLayout'=> $self->NoLayout)) . '"><img src="'.$Urlpath3.'" title="'.$self->translator->trans('pi.move-down').'" width="16" /></a>';
                    $linkDelete='';
                    if($node['enabled']){
                        $linkDelete    = '<a href="' . $self->generateUrl('admin_content_rub_node_remove', array('id' => $node['id'], 'category'=>$self->category, 'NoLayout'=> $self->NoLayout)) . '"><img src="'.$Urlpath4.'" title="'.$self->translator->trans('pi.delete').'"  width="16" /></a>';    
                    }     

                    if($node['lvl'] == 1){
                        $linkFavorite   = '<a href="'.$self->generateUrl('admin_content_meahome') .'"><img src="'.$Urlpath5.'" title="Mise en avant"  width="16" /></a>';
                    } else {
                        $linkFavorite = '';
                    }

                    $linkNode .= $linkAdd . '&nbsp;&nbsp;&nbsp;' . $linkEdit . '&nbsp;&nbsp;&nbsp;' . $linkUp . '&nbsp;&nbsp;&nbsp;' . $linkDown . '&nbsp;&nbsp;&nbsp;' . $linkDelete . '&nbsp;&nbsp;&nbsp;' . $linkFavorite. '&nbsp;&nbsp;&nbsp;' . $linkRubFront;

                    if ( ($node['lft'] == -1) && ($node['rgt'] == 0) )  $linkNode .= '</div>'; // if ( ($node['lft'] == -1) && ($node['rgt'] !== 0) )
                    if ( ($node['lft'] == -1) && ($node['rgt'] !== 0) ) $linkNode .= '</div>'; // if ( ($node['lft'] == -1) && ($node['rgt'] !== 0) )
                    return $linkNode;
                }
        );
        
        // we repair the tree
        $em->getRepository("PluginsContentBundle:Rub")->recover();
        $result = $em->getRepository("PluginsContentBundle:Rub")->verify();
        
        $node   = $this->container->get('request')->query->get('node');
        if (!empty($node) ){
            $node  = $em->getRepository("PluginsContentBundle:Rub")->findNodeOr404($node, $locale,'object');
        } else {
            $node = null;
        }
        
        $nodes         = $em->getRepository("PluginsContentBundle:Rub")->getAllTree($locale, $category, 'array', false, false, $node);
        $tree        = $em->getRepository("PluginsContentBundle:Rub")->buildTree($nodes, $options);        
        
        return $this->render("PluginsContentBundle:Rub:$template", array(
            'tree'          => $tree,
            'category'    => $category,
            'NoLayout'      => $NoLayout,
        ));
    }  
    
    /**
     * Move the node up in the same level
     * 
     * @Secure(roles="ROLE_USER")
        * @param int $id
        * @param string $category
        * @access    public
        * 
        * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function moveUpAction($id, $category)
    {
        $em                 = $this->getDoctrine()->getManager();
        $locale             = $this->container->get('request')->getLocale();
        $NoLayout        = $this->container->get('request')->query->get('NoLayout');
        
        $node             = $em->getRepository("PluginsContentBundle:Rub")->findNodeOr404($id, $locale);
        $entity_node_pos = $node->getRoot();
        
         if ($node->getLvl() == NULL){
            $all_root_nodes     = $em->getRepository("PluginsContentBundle:Rub")->getAllByCategory($category, null, "ASC")->getQuery()->getResult();
            foreach($all_root_nodes as $key => $routeNode){
                $routenode_pos = $routeNode->getRoot();
                if ( $routenode_pos < $entity_node_pos ){
                    $em->getRepository("PluginsContentBundle:Rub")->moveRoot($entity_node_pos, -100);
                    $em->getRepository("PluginsContentBundle:Rub")->moveRoot($routenode_pos, $entity_node_pos);
                    $em->getRepository("PluginsContentBundle:Rub")->moveRoot($entity_node_pos, $routenode_pos);
                }
            }
            $em->flush();
         }else
            $em->getRepository("PluginsContentBundle:Rub")->moveUp($node);
         
         // we repair the tree
         $em->getRepository("PluginsContentBundle:Rub")->recover();
         $result = $em->getRepository("PluginsContentBundle:Rub")->verify();

        return $this->redirect($this->generateUrl('admin_content_rub_tree', array('category'=>$category, 'NoLayout' => $NoLayout)));
    }
    
    /**
     * Move the node down in the same level
     * 
     * @Secure(roles="ROLE_USER")
        * @param int $id
        * @param string $category
        * @access    public
        * 
        * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function moveDownAction($id, $category)
    {
        $em                 = $this->getDoctrine()->getManager();
        $locale             = $this->container->get('request')->getLocale();
        $NoLayout        = $this->container->get('request')->query->get('NoLayout');
        
        $node             = $em->getRepository("PluginsContentBundle:Rub")->findNodeOr404($id, $locale);
        $entity_node_pos = $node->getRoot();
         
        if ($node->getLvl() == NULL){
            $all_root_nodes     = $em->getRepository("PluginsContentBundle:Rub")->getAllByCategory($category, null, "DESC")->getQuery()->getResult();
            foreach($all_root_nodes as $key => $routeNode){
                $routenode_pos = $routeNode->getRoot();
                if ( $routenode_pos > $entity_node_pos ){
                    $em->getRepository("PluginsContentBundle:Rub")->moveRoot($entity_node_pos, -100);
                    $em->getRepository("PluginsContentBundle:Rub")->moveRoot($routenode_pos, $entity_node_pos);
                    $em->getRepository("PluginsContentBundle:Rub")->moveRoot($entity_node_pos, $routenode_pos);
                }
            }
            $em->flush();
        }else
            $em->getRepository("PluginsContentBundle:Rub")->moveDown($node);

        // we repair the tree
        $em->getRepository("PluginsContentBundle:Rub")->recover();
        $result = $em->getRepository("PluginsContentBundle:Rub")->verify();
        
        return $this->redirect($this->generateUrl('admin_content_rub_tree', array('category'=>$category, 'NoLayout' => $NoLayout)));
    }
    
    /**
     * Removes given $node from the tree and reparents its descendants
     * 
     * @Secure(roles="ROLE_USER")
        * @param int $id
        * @param string $category
        * @access    public
        * 
        * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function removeAction($id, $category)
    {
        $em        = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();
        $node    = $em->getRepository("PluginsContentBundle:Rub")->findNodeOr404($id, $locale);
        
        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
    
        //$em->getRepository("PluginsContentBundle:Rub")->removeFromTree($node);
        if (!$node) {
                throw ControllerException::NotFoundException('Rub');
        }

        try {
            	if (method_exists($node, 'setArchived')) {
            		$node->setArchived(true);
            	}
            	if (method_exists($node, 'setEnabled')) {
            		$node->setEnabled(false);
            	}
            	if (method_exists($node, 'setArchiveAt')) {
            		$node->setArchiveAt(new \DateTime());
            	}
            	if (method_exists($node, 'setPosition')) {
            		$node->setPosition(null);
            	}

                $em->persist($node);
            	$em->flush();
                
                $this->container->get('request')->getSession()->getFlashBag()->add('notice', 'Rubrique supprimée avec succès');

        } catch (\Exception $e) {
                $this->container->get('request')->getSession()->getFlashBag()->add('notice', 'pi.session.flash.wrong.undelete');
        }        
        return $this->redirect($this->generateUrl('admin_content_rub_tree', array('category'=>$category, 'NoLayout' => $NoLayout)));
    }
}