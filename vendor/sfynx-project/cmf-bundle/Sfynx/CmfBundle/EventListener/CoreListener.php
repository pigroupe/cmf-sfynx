<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Core
 * @package    EventListener
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-01-30
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

use Sfynx\CmfBundle\EventListener\abstractListener;
use Sfynx\AuthBundle\Entity\User;
use Sfynx\AuthBundle\Entity\Langue;
use Sfynx\AuthBundle\Entity\Layout;
use Sfynx\CmfBundle\Entity\Page;
use Sfynx\CmfBundle\Entity\TranslationPage;
use Sfynx\CmfBundle\Entity\Block;
use Sfynx\CmfBundle\Entity\Widget;
use Sfynx\CmfBundle\Entity\TranslationWidget;
use Sfynx\AuthBundle\Entity\Role;
use Sfynx\CmfBundle\Entity\HistoricalStatus;
use Sfynx\CmfBundle\Repository\PageRepository;

/**
 * abstract listener manager.
 * This event is called after an entity is constructed by the EntityManager.
 *
 * @subpackage   Core
 * @package    EventListener
 * @abstract
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class CoreListener extends abstractListener
{
    /**
     * We create the cached file of Roles.
     *
     * @param LifecycleEventArgs $eventArgs The LifecycleEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    final protected function _Heritage_roles(LifecycleEventArgs $eventArgs)
    {
        $entity         = $eventArgs->getEntity();
        $entityManager  = $eventArgs->getEntityManager();        
        // If  autentication user, we set the persist of the Page entity
        if ($this->isUsernamePasswordToken() 
                && ($entity instanceof Role)
        ){
            // we register the hierarchy roles in the heritage.jon file in the cache
            if ($this->container->get('sfynx.auth.role.factory')->setJsonFileRoles()) {
                $this->setFlash('pi.session.flash.rolecache.created');
            }
        }
    }  

    /**
     * We create the cached file of languages.
     *
     * @param LifecycleEventArgs $eventArgs The LifecycleEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _locales_language_json_file(LifecycleEventArgs $eventArgs)
    {
    	$entity         = $eventArgs->getEntity();
    	$entityManager  = $eventArgs->getEntityManager();
    	// If  autentication user, we set the persist of the Page entity
    	if ($this->isUsernamePasswordToken() 
                && ($entity instanceof Langue)
        ) {
            $this->container
                ->get('sfynx.auth.locale_manager')
                ->setJsonFileLocales();
    	}
    } 

    /**
     * We remove json file Etag of Page and Widget.
     *
     * @param LifecycleEventArgs $eventArgs         The LifecycleEventArgs class
     * @param boolean     	 $delete_cache_only True to delete only cache
     * 
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _deleteJsonFileEtag($eventArgs, $delete_cache_only = false)
    {
    	$entity = $eventArgs->getEntity();
    	if ($this->isUsernamePasswordToken() 
            && ($entity instanceof Page 
                || $entity instanceof TranslationPage 
                || $entity instanceof Widget
            )
    	) {
    	    $this->container
                ->get('pi_app_admin.manager.page')
                ->cacheDelete($entity, $delete_cache_only);
	}
    }   
    
    /**
     * We remove json file Etag of Page and Widget.
     *
     * @param object $eventArgs The LifecycleEventArgs class
     * @param string $type      ['insert', 'update', 'remove']
     * 
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _JsonFilePage($eventArgs, $type)
    {
    	$entity = $eventArgs->getEntity();
    	if ($this->isUsernamePasswordToken() 
            && ($entity instanceof Page
                || $entity instanceof TranslationPage 
            )
    	) {
    	    $this->container
                ->get('pi_app_admin.manager.page')
                ->cachePage($entity, $type);
	}
    }      
    
    /**
     * We remove twig cached file of Page, Widget and translationWidget template.
     *
     * @param LifecycleEventArgs $eventArgs The LifecycleEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _TwigCache($eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $is_refresh_authorized = $this->container
                ->getParameter('pi_app_admin.page.refresh.allpage');        
        if ($this->isUsernamePasswordToken() 
            && $is_refresh_authorized 
            && (  $entity instanceof Page
                || $entity instanceof TranslationPage
                || $entity instanceof Widget
                || $entity instanceof TranslationWidget
            )
        ) {
            $all_locales = $this->container
                ->get('sfynx.auth.locale_manager')
                ->getAllLocales();
            $names = $this->_recursive($eventArgs, $entity, $all_locales);
//              if ($names && is_array($names)){
//                  krsort($names);
//                  print_r($names);
//              }
//             exit;
        }
    }    
    
    /**
     * We find all template names of a page and these nodes.
     *
     * @param LifecycleEventArgs $eventArgs   The LifecycleEventArgs class
     * @param mixed              $entity      A page, translationpage, widget or translationwidget entity
     * @param array              $all_locales List of all locales
     * 
     * @return void
     * @access private
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    private function _recursive($eventArgs, $entity, $all_locales)
    {
    	if ($entity instanceof TranslationPage) {
            $entity = $entity->getPage();
    	}
        // we set the persist of the Page entity
        if ($entity instanceof Page) {
            $type = 'page:';
        } elseif ($entity instanceof Widget) {
            $type = 'widget:';
        } elseif ($entity instanceof TranslationWidget) {
            $type = 'transwidget:';
        } else {
            return false;
        }
        $names = array();
        foreach ($all_locales as $key => $lang) {
            if (!method_exists($entity, 'getLayout') 
                    || ($entity->getLayout() instanceof Layout) 
            ) {
                // we refresh the cache
                $name             = $type.$entity->getId().':'.$lang;
                $names[$name]     = $name;
                $this->container
                        ->get('pi_app_admin.manager.page')
                        ->cacheRefreshByname($name);                
                // we refresh the cache of all widgets of the page
                if ($entity instanceof Page) {
                    $this->container
                            ->get('pi_app_admin.manager.page')
                            ->setPageByRoute($entity->getRouteName(), true);
                    $this->container
                            ->get('pi_app_admin.manager.page')
                            ->cacheRefresh();
                }                
                if ($entity instanceof Widget) {  
                    // we have to warm up all translations which are linked to it.
                    if (!$entity->getTranslations()->isEmpty()) {
                        foreach($entity->getTranslations()->toArray() as $key => $translationWidget) {
                            if ($translationWidget->getLangCode()->getId() == $lang){
                                $name_trans         = 'transwidget:'.$translationWidget->getId().':'.$lang;
                                $names[$name_trans] = $name_trans;                                
                                // we refresh the cache
                                $this->container
                                        ->get('pi_app_admin.manager.page')
                                        ->cacheRefreshByname($name_trans, true);
                            }
                        }
                    }                    
                    // if the entity is linked to a page
                    if ($entity->getBlock() instanceof Block) {
                        $names = array_merge(
                            $names,
                            $this->_recursive(
                                $eventArgs,
                                $entity->getBlock()->getPage(),
                                $all_locales
                            )
                        );
                    } else {
                        // We check the permission in config.
                        $is_refresh_snippet_authorized = $this->container
                                ->getParameter('pi_app_admin.page.refresh.allpage_containing_snippet');
                        if ($is_refresh_snippet_authorized) {
                        	// we get all widgets which use the gedmo snippet
                            $all_widget_used_snippet = $this->getRepository('Widget')
                                    ->getWidgetByOptions('gedmo', 'snippet', '<id>'.$entity->getId().'</id>')->getQuery()->getResult();
                            if (is_array($all_widget_used_snippet)) {
                                foreach ($all_widget_used_snippet as $k => $widget) {
                                    // if the entity is linked to a page
                                    if ($widget->getBlock() instanceof Block) {
                                        $names = array_merge(
                                                $names,
                                                $this->_recursive(
                                                    $eventArgs, 
                                                    $widget->getBlock()->getPage(), 
                                                    $all_locales
                                                )
                                        );
                                    }
                                }
                            }    
                        }
                    }
                }                
                // if the entity is a translation, we have to warm up the widget which is linked to it.
                if ($entity instanceof TranslationWidget
                    && ($entity->getWidget() instanceof Widget))
                {
                    // if the widget of the TranslationWidget is a snippet,
                    // and if the language of the TranslationWidget has been changed or not,
                    // we have to warm up all pages which are used by the snippet 
                    if (!($entity->getWidget()->getBlock() instanceof Block) ) {                            
                        // We check the permission in config.
                        $is_refresh_snippet_authorized = $this->container
                                ->getParameter('pi_app_admin.page.refresh.allpage_containing_snippet');
                        if ($is_refresh_snippet_authorized) {
                            // we get all widgets which use the content snippet 
                            $all_widget_used_snippet = $this->getRepository('Widget')
                                    ->getWidgetByOptions('content', 'snippet', '<id>'.$entity->getWidget()->getId().'</id>')->getQuery()->getResult();
                            if ( is_array($all_widget_used_snippet) ) {
                                foreach ($all_widget_used_snippet as $k => $widget){
                                    // if the entity is linked to a page
                                    if ($widget->getBlock() instanceof Block){
                                        $names = array_merge(
                                            $names,
                                            $this->_recursive(
                                                $eventArgs,
                                                $widget->getBlock()->getPage(), 
                                                $all_locales
                                            )
                                        );
                                    }                                    
                                }
                            }
                        }
                    } else {
                        $names = array_merge(
                            $names, 
                            $this->_recursive(
                                $eventArgs,
                                $entity->getWidget(), 
                                $all_locales
                            )
                        );
                    }                        
                }                
            }            
        }
        
        return $names;        
    }
    
    /**
     * We link the entity widget type to the page.
     *
     * @param object $eventArgs The object class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _widgetListener($eventArgs)
    {
        $entity        = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();        
        if ( $entity instanceof Widget ){
            // If  autentication user
            if ($this->isUsernamePasswordToken()) {
                $plugin    = strtolower($entity->getPlugin());
                $action    = strtolower($entity->getAction());
                $xmlConfig = $entity->getConfigXml();                
                if (($plugin == "gedmo") 
                        && ($action == "listener")
                ){
                    // if the configXml field of the widget isn't configured correctly.
                    try {
                        $xmlConfig = new \Zend_Config_Xml($xmlConfig);
                    } catch (\Exception $e) {
                        return null;
                    }
                    try {
                        $controller = $xmlConfig->widgets->gedmo->controller;
                        $params     = $xmlConfig->widgets->gedmo->params->toArray();
                        $id         = $params["id"];
                    } catch (\Exception $e) {
                        return null;
                    }                    
                    try {
                        $values             = explode(':', $controller);
                        $entity_widget_name = str_replace('\\\\', '\\', strtolower($values[1]));
                        $method_widget_name = strtolower($values[2]);                        
                        $entity_widget      = $this->container
                                ->get('pi_app_gedmo.repository')
                                ->getRepository($entity_widget_name)->find($id);
                        //$entity_widget_table    = $this->container->get('pi_app_gedmo.repository')->getRepository($entity_widget_name)->getClassName();
                        $entity_widget_table= $this->getOwningTable($eventArgs, $entity_widget);
                        
                        if (is_object($entity_widget) 
                            && method_exists($entity_widget, 'getPage') 
                            && method_exists($entity_widget, 'setPage')
                            //&& !($entity_widget->getPage() instanceof \Sfynx\CmfBundle\Entity\Page)
                            && ($entity->getBlock() instanceof Block)
                            && ($entity->getBlock()->getPage() instanceof Page)
                        ){                                
                            $page_id    = $entity->getBlock()
                                    ->getPage()
                                    ->getId();
                            try {
                                $query = "UPDATE $entity_widget_table mytable SET mytable.page_id ='$page_id' WHERE mytable.id = ?";
                                $this->_connexion($eventArgs)->executeUpdate($query, array($id));
                                //$table         = $this->container->get('pi_app_gedmo.repository')->getRepository(get_class($entity))->getClassName();
                                //$query = "UPDATE $table mytable SET mytable.page_id='$page_id' WHERE mytable.id = ?";
                                //$this->container->get('pi_app_gedmo.repository')->getRepository(get_class($entity))
                                //->getEntityManager()->createQuery($query)->getSingleScalarResult();
                            } catch (\Exception $e) {
                                return null;
                            }
                        }
                    } catch (\Exception $e) {
                        return null;
                    }
                }
            }
        }
    }    
    
    /**
     * We create the xml content about all layout block information.
     *
     * @param object $eventArgs THe object class
     * 
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _Layout($eventArgs)
    {
        $entity        = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();    
        // we set the persist of the Page entity
        if ($entity instanceof Layout) {
            // If  autentication user
            if ($this->isUsernamePasswordToken()) {
                $init_pc_layout     = str_replace("\\", "/", $entity->getFilePc());
                $init_mobile_layout = str_replace("\\", "/", $entity->getFileMobile());
                if (empty($init_pc_layout)) {
                    $init_pc_layout = $this->container
                            ->getParameter('sfynx.auth.layout.init.pc.template');
                }
                if (empty($init_mobile_layout)) {
                    $init_mobile_layout = $this->container
                            ->getParameter('sfynx.auth.layout.init.mobile.template');
                }
                $path_pc_layout     = realpath($this->container
                        ->get('kernel')
                        ->locateResource($this->container->getParameter('sfynx.auth.theme.layout.front.pc.path') . $init_pc_layout));
                $path_mobile_layout = realpath($this->container
                        ->get('kernel')
                        ->locateResource($this->container->getParameter('sfynx.auth.theme.layout.front.mobile.path') . $init_mobile_layout . '/' . 'modele.html.twig'));
                // if the both path layout exist.
                if (!empty($path_pc_layout) && !empty($path_mobile_layout)) {
                    $content_file_pc         = $this->container
                            ->get('sfynx.tool.file_manager')
                            ->getFileContent($path_pc_layout);
                    $content_file_mobile     = $this->container
                            ->get('sfynx.tool.file_manager')
                            ->getFileContent($path_mobile_layout);
                    // Gets the different blocks from Twig layout
                    if (preg_match_all('#{% block (?P<block_name>(.*)) %}#sU', $content_file_pc, $matches_pc)
                        && preg_match_all('#{% block (?P<block_name>(.*)) %}#sU', $content_file_mobile, $matches_mobile)
                    ){
                        $tabs = array_unique(
                            array_merge(
                                $matches_pc['block_name'],
                                $matches_mobile['block_name']
                            )
                        );
                    } else {
                        $tabs = array();
                    }
                    $tabs = $this->container
                            ->get('sfynx.tool.array_manager')
                            ->TrimArray($tabs);
                    // we get the list of all global blocks.
                    $pageManager = $this->container->get('pi_app_admin.manager.page');
                    $unset_values= $pageManager::$global_blocks;
                    // we create the xml content about all layout block information.
                    $source_xml  = "<?xml version=\"1.0\"?>\n";
                    $source_xml .= "<config>\n";
                    $source_xml .= "    <blocks>\n";
                    foreach ($tabs as $key => $block_name) {
                        if (!in_array($block_name, $unset_values)) {
                            $source_xml .= "        <name>$block_name</name>\n";
                        }
                    }
                    $source_xml .= "    </blocks>\n";
                    $source_xml .= "</config>\n";
                    // we insert the config_xml value.
                    $entity->setConfigXml($source_xml);
                } else {
                    $this->setFlash('pi.session.flash.layout.notexist', 'warning');
                }
            }
        }
    }
    
    /**
     * We try to update the route name of the home page.
     *
     * @param PreUpdateEventArgs $eventArgs The PreUpdateEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _NoUpdate_RouteName_HomePage(PreUpdateEventArgs $eventArgs)
    {
        $entity_page   = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();
        // we set the persist of the Page entity
        if ($entity_page instanceof Page) {
            $isflash = false;
            if (($eventArgs->hasChangedField('route_name') 
                    && ($eventArgs->getOldValue('route_name') == 'home_page'))
                    || ($entity_page->getRouteName() == 'home_page')
            ) {
                $entity_page->setRouteName('home_page');
                $entity_page->setEnabled(true);
                $entity_page->setMetaContentType(PageRepository::TYPE_TEXT_HTML);
                $isflash = true;
            }
            if ($isflash) {
                $this->setFlash('pi.session.flash.right.homepage.notchange', 'warning');
            }
        }
    }
    
    /**
     * we delete the home page
     * or a translation of this one,
     * or a block of this one,
     * or a widget of this one.
     *
     * @param LifecycleEventArgs $eventArgs The LifecycleEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _Undelete_HomePage(LifecycleEventArgs $eventArgs)
    {
        $entity        = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();
        if (($entity instanceof Page)
                && ($entity->getRouteName() == 'home_page' )
        ) {
            $entityManager->getUnitOfWork()->detach($entity);
            $this->setFlash('pi.session.flash.right.homepage.undelete');
        }
        if (($entity instanceof TranslationPage)
                && ($entity->getPage() instanceof Page)
                && ($entity->getPage()->getRouteName() == 'home_page' )
        ) {
            $entityManager->getUnitOfWork()->detach($entity);
            $this->setFlash('pi.session.flash.right.homepage.undelete');
        }
        if (($entity instanceof Block)
                && ($entity->getPage() instanceof Page)
                && ($entity->getPage()->getRouteName() == 'home_page' )
        ) {
            $entityManager->getUnitOfWork()->detach($entity);
            $this->setFlash('pi.session.flash.right.homepage.undelete');
        }
        $is_permission_delete_widget_homepage = $this->container
                ->getParameter('pi_app_admin.page.homepage_deletewidget');
        // if we want to undelete all widgets of the home page
        if (!$is_permission_delete_widget_homepage
                && ($entity instanceof Widget)
                && ($entity->getBlock() instanceof Block)
                && ($entity->getBlock()->getPage() instanceof Page)
                && ($entity->getBlock()->getPage()->getRouteName() == 'home_page' )
        ) {
            $entityManager->getUnitOfWork()->detach($entity);
            $this->setFlash('pi.session.flash.right.homepage.undelete');
        }
    }    
    
    /**
     * If  autentication user and the user has the permission to delete a page,
     * we delete all caches of the page and the row in relation with the pi_routing table.
     *
     * @param LifecycleEventArgs $eventArgs The LifecycleEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _Delete_Permission_Page_ByUser(LifecycleEventArgs $eventArgs){
        $entity        = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();
        // If  autentication user and the user has the permission to delete a page
        if ($this->isUsernamePasswordToken() 
                && (in_array('DELETE', $this->getUserPermissions()) 
                    || in_array('ROLE_SUPER_ADMIN', $this->getUserRoles())
                )
        ) {
           $no_permission  = $this->_permission_management_Page_ByUser($eventArgs);
           if ($no_permission) {
               $entityManager->getUnitOfWork()->detach($entity);                
               $this->setFlash('pi.session.flash.right.page.management_by_user_only', 'only');
           } else {    
               if ($entity instanceof Page){                
                    // if we try to delete a page other than the home page.
                    if ($entity->getRouteName() != 'home_page') {
                        // we delete the row in relation with the pi_routing table
                        $query = "SELECT id FROM pi_routing WHERE route = ?";
                        $id    = $this->_connexion($eventArgs)
                                ->fetchColumn($query, array($entity->getRouteName()));
                        $this->_connexion($eventArgs)
                                ->delete('pi_routing', array('id'=>$id));
                        // we delete all caches of the page
                        $urls  = $this->container
                                ->get('pi_app_admin.manager.page')
                                ->getUrlByPage($entity);
                        foreach($urls as $locale => $url){
                            $name = "page:" .$entity->getId() . ':' . $locale . ':' . $url;
                            try {
                                $this->container->get('pi_app_admin.caching')->invalidate($name);
                            } catch (\Exception $e) {
                            }
                        }
                    }
               }
           }   
        }
    }        
    
    /**
     * we detach the permission of update a page.
     *
     * @param PreUpdateEventArgs $eventArgs The PreUpdateEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _Update_Permission_Page_ByUser(PreUpdateEventArgs $eventArgs)
    {
        $entity        = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();        
        $no_permission = $this->_permission_management_Page_ByUser($eventArgs);
        if ($no_permission) {
            $class = $entityManager->getClassMetadata(get_class($entity));
            $entityManager->getUnitOfWork()->computeChangeSet($class, $entity);        
            $this->setFlash(
                'pi.session.flash.right.page.management_by_user_only',
                'only'
            );
        } else {
            if ($entity instanceof Page) {
                // if we try to delete a page other than the home page.
                if (($entity->getRouteName() != 'home_page' ) 
                	&& $eventArgs->hasChangedField('route_name')
                ) {
                    // we delete the row in relation with the pi_routing table
                    $query  = "SELECT id FROM pi_routing WHERE route = ?";
                    $id     = $this->_connexion($eventArgs)
                            ->fetchColumn($query, array($entity->getRouteName()));
                    $this->_connexion($eventArgs)
                            ->delete('pi_routing', array('id'=>$id));
                }
            }            
        }
    }
    
    /**
     * we detach the permission of create a page.
     *
     * @param PreUpdateEventArgs $eventArgs The PreUpdateEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _Persist_Permission_Page_ByUser($eventArgs)
    {
        $entity        = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();        
        $no_permission = $this->_permission_management_Page_ByUser($eventArgs);
        if ($no_permission){
            $entityManager->getUnitOfWork()
                    ->scheduleOrphanRemoval($entity);
            $this->setFlash(
                'pi.session.flash.right.page.management_by_user_only',
                'only'
            );
        }
    }    

    /**
     * we detach the permission of a page.
     *
     * @param PreUpdateEventArgs $eventArgs The PreUpdateEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    private function _permission_management_Page_ByUser($eventArgs)
    {
        $entity        = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();
        $is_permission_management_page_by_user_only = $this->container
                ->getParameter('pi_app_admin.page.management_by_user_only');
        if ($this->isUsernamePasswordToken() 
                && $is_permission_management_page_by_user_only
        ) {
            if (($entity instanceof Page)
                    && !(in_array('ROLE_ADMIN', $this->getUserRoles())
                            || in_array('ROLE_SUPER_ADMIN', $this->getUserRoles()) 
                            || in_array('ROLE_CONTENT_MANAGER', $this->getUserRoles())
                    )
                    && ($entity->getUser() instanceof User)
                    && ($entity->getUser()->getId() != $this->getToken()->getUser()->getId() )
            ) {
                $entity->setUpdatedAt(new \DateTime());
                $no_permission = true;
            } elseif (($entity instanceof TranslationPage)
                    && !(in_array('ROLE_ADMIN', $this->getUserRoles()) 
                        || in_array('ROLE_SUPER_ADMIN', $this->getUserRoles()) 
                        || in_array('ROLE_CONTENT_MANAGER', $this->getUserRoles())
                    )
                    && ($entity->getPage()->getUser() instanceof User)
                    && ($entity->getPage()->getUser()->getId() != $this->getToken()->getUser()->getId() )
            ) {
                $entity->setUpdatedAt(new \DateTime());
                $no_permission = true;
            } elseif (($entity instanceof Block)
                    && !(in_array('ROLE_ADMIN', $this->getUserRoles()) 
                        || in_array('ROLE_SUPER_ADMIN', $this->getUserRoles()) 
                        || in_array('ROLE_CONTENT_MANAGER', $this->getUserRoles())
                    )
                    && ($entity->getPage()->getUser() instanceof User)
                    && ($entity->getPage()->getUser()->getId() != $this->getToken()->getUser()->getId() )
            ) {
                $entity->setUpdatedAt(new \DateTime());
                $no_permission = true;
            } elseif (($entity instanceof Widget)
                    && !(in_array('ROLE_ADMIN', $this->getUserRoles()) 
                        || in_array('ROLE_SUPER_ADMIN', $this->getUserRoles()) 
                        || in_array('ROLE_CONTENT_MANAGER', $this->getUserRoles())
                    )
                    && (method_exists($this->getToken(), 'getUser'))
                    && ($entity->getBlock() instanceof Block)
                    && ($entity->getBlock()->getPage()->getUser() instanceof User)
                    && ($entity->getBlock()->getPage()->getUser()->getId() != $this->getToken()->getUser()->getId() )
            ) {
                $entity->setUpdatedAt(new \DateTime());
                $no_permission = true;
            } elseif (($entity instanceof TranslationWidget)
                    && !(in_array('ROLE_ADMIN', $this->getUserRoles()) 
                        || in_array('ROLE_SUPER_ADMIN', $this->getUserRoles()) 
                        || in_array('ROLE_CONTENT_MANAGER', $this->getUserRoles())
                    )
                    && ($entity->getWidget()->getBlock() instanceof Block)
                    && ($entity->getWidget()->getBlock()->getPage()->getUser() instanceof User)
                    && ($entity->getWidget()->getBlock()->getPage()->getUser()->getId() != $this->getToken()->getUser()->getId() )
            ) {
                $entity->setUpdatedAt(new \DateTime());
                $no_permission = true;
            } else {
                $no_permission = false;
            }                  
        } else {
            $no_permission = false;
        }
       
        return $no_permission;
    }    
    
    /**
     * We create all layout block in terms of the layout information.
     *
     * @param LifecycleEventArgs $eventArgs The LifecycleEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _Create_Block_Page(LifecycleEventArgs $eventArgs)
    {
        $entity_page   = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();
        // If  autentication user
        if ($this->isUsernamePasswordToken() 
            && ($entity_page instanceof Page) 
            && ($entity_page->getLayout() instanceof Layout)
        ){
            $layout_blocks = (string) $entity_page->getLayout()->getConfigXml();
            // if the configXml field of the layout entity isn't configured correctly.
            try {
                $layout_blocks = new \Zend_Config_Xml($layout_blocks);
            } catch (\Exception $e) {
                $layout_blocks = null;
            }                    
            if (is_object($layout_blocks->get('blocks'))
                    && $layout_blocks->get('blocks')
                    && $layout_blocks->blocks->get('name')
            ){
                $layout_blocks = $layout_blocks->blocks->name->toArray();
            } else { 
                $layout_blocks = null;
            }
            if (!is_null($layout_blocks)) {
                // we get all block names information of the page entity.
                $page_blocks      = $entity_page->getBlocks()->toArray();
                $page_blocks_name = array();
                foreach ($page_blocks as $k => $v) {
                    $page_blocks_name[] = $v->getName();
                }                            
                // we create all the blocks that have not already been created.
                foreach ($layout_blocks as $key => $block_name) {
                    if (!in_array($block_name, $page_blocks_name)) {
                        $entity_block = new Block();
                        $entity_block->setCreatedAt(new \DateTime(date('Y-m-d')));
                        $entity_block->setUpdatedAt(new \DateTime(date('Y-m-d')));
                        $entity_block->setPage($entity_page);
                        $entity_block->setName($block_name);
                        $entity_block->setPosition($key);
                        $entity_block->setConfigCssClass('block_'.$block_name);
                        $entity_block->setEnabled(true);
                        // we add the entity to be persisted.
                        $this->_addPersistEntities($entity_block);
                    }
                }
            }
        } // endif
    }  
    
    /**
     * We check if the url of the page does not already exist.
     *
     * @param LifecycleEventArgs $eventArgs The LifecycleEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _single_SlugByPage($eventArgs)
    {
        $entity        = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();
        $no_permission = false;        
        // If  autentication user
        if ($this->isUsernamePasswordToken()) {
            if ($entity instanceof Page ) {
                $page = $entity;
            } elseif ($entity instanceof TranslationPage) {
                $page = $entity->getPage();
            } else {
                $page = null;
            }
            if (is_null($page)) {
                return $no_permission;
            }
            if (($page instanceof Page) 
                && (!$page->getTranslations()->isEmpty())
            ) {                
                // we delete all references of the page in the index file.
                $this->container
                        ->get('pi_app_admin.manager.search_lucene')
                        ->deletePage($page);                                    
                // we get all urls of a page
                $locales = $this->container
                        ->get('pi_app_admin.manager.page')
                        ->getUrlByPage($page, 'sql');
                $no_permission_urls = null;
                $route_page         = $page->getRouteName();
                // we select all rows which contain the url value
                foreach ($locales as $key => $value) {
                    // if the urls of the page are not those of the home page.
                    if (!empty($value)) {
                        $query = "SELECT id, locales FROM pi_routing WHERE locales LIKE '%$value%' AND route != '$route_page' ";
                        $rows  = $this->_connexion($eventArgs)->fetchAll($query);                        
                        foreach ($rows as $row) {
                            $locales = json_decode($row['locales']);
                            foreach ($locales as $lang => $slug) {
                                $value = str_replace("\\\\\\\\\/", "/", $value);
                                if ($slug == $value){
                                    $no_permission        = true;
                                    $no_permission_urls[] = '« ' . str_replace("\\\\\\\\\/", "/", $value) . ' »';
                                }                                    
                            }
                        }
                    }
                }
            } // endif
        } // endif
        // If one of the urls of the page already existe, we forbid those registrations.
        if ($no_permission){
            if(($entity instanceof Page) 
                    && ($eventArgs instanceof PreUpdateEventArgs)
            ) {
                if (
                    $eventArgs->hasChangedField('url')
                    &&
                    ($eventArgs->getOldValue('url') != $eventArgs->getNewValue('url'))
                ) {
                    $entity->setUrl('undefined-value');
                }
            } elseif ($entity instanceof TranslationPage) {
                $entity->setSlug('undefined-value');
            }
            $urls = implode(', ', $no_permission_urls);
            $message = $this->container
                    ->get('translator')
                    ->trans('pi.session.flash.page.slug.exist', array('url'=> $urls));
            $this->setFlash($message, 'only');
        }
    }   
    
    /**
     * we manage the search lucene.
     *
     * @param LifecycleEventArgs $eventArgs The LifecycleEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    final protected function _Search_lucene(LifecycleEventArgs $eventArgs, $action)
    {
        $entity        = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();
        // We check the permission in config.
        $is_indexation_authorized = $this->container
                ->getParameter('pi_app_admin.page.indexation_authorized_automatically');
        if ($this->isUsernamePasswordToken() 
                && $is_indexation_authorized
        ) {
            if ($entity instanceof Page) {
                $page = $entity;
            } elseif ($entity instanceof TranslationPage) {
                $page = $entity->getPage();
            } elseif ($entity instanceof Block){
                $page = $entity->getPage();
            } elseif ($entity instanceof Widget) {
                if (!is_null($entity->getBlock())) {
                    $page = $entity->getBlock()->getPage();
                } else {
                    $page = null;                
                }
            } elseif ($entity instanceof TranslationWidget) {
                if (!is_null($entity->getBlock())) {
                    $page = $entity->getWidget()->getBlock()->getPage();
                } else {
                    $page = null;                
                }
            } else {
                $page = null;
            }
            if (is_null($page)) {
                return false;    
            }    
            if (in_array($action, array('insert', 'update'))) {
                $this->container
                    ->get('pi_app_admin.manager.search_lucene')
                    ->indexPage($page);
            } elseif ($action == 'delete') {
                $this->container
                    ->get('pi_app_admin.manager.search_lucene')
                    ->deletePage($page);
            }
        }
    }
    
    /**
     * We register in the cache the route of the page
     *
     * @param LifecycleEventArgs $eventArgs The LifecycleEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    final protected function _updateCacheUrlGenerator(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if ($this->isUsernamePasswordToken() 
                && ($entity instanceof Page)
                && $entity->getEnabled()
                && !$entity->getTranslations()->isEmpty()) {
        } elseif ($this->isUsernamePasswordToken() 
                && ($entity instanceof TranslationPage) ) {
        } else {
            return false;
        }
        $routeCacheManager = $this->container
                ->get('sfynx.tool.route.cache');
        $routeCacheManager->setGenerator();
        $routeCacheManager->setMatcher();
    }
    
    /**
     * we create the historic change of TranslationPage status.
     *
     * @param PreUpdateEventArgs $eventArgs The PreUpdateEventArgs class
     *
     * @return void
     * @access protected
     * @final
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    final protected function _TranslationPage(PreUpdateEventArgs $eventArgs)
    {
        $entity        = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();
        // we set the persist of the Page entity
        if ($this->isUsernamePasswordToken()
                && $entity instanceof TranslationPage
        ){
            if ($eventArgs->hasChangedField('status')
            	&& ($eventArgs->getOldValue('status') != $eventArgs->getNewValue('status'))
            ) {
                $historicalStatus = new HistoricalStatus();
                $historicalStatus->setCreatedAt(new \DateTime(date('Y-m-d')));
                $historicalStatus->setPageTranslation($entity);
                $historicalStatus->setStatus($entity->getStatus());
                $historicalStatus->setComment('Historical status change');
                $historicalStatus->setCreatedAt(new \DateTime());
                $historicalStatus->setEnabled(true);
                // we add the entity to be persisted.
                $this->_addPersistEntities($historicalStatus);
            }
        }
    }
}
