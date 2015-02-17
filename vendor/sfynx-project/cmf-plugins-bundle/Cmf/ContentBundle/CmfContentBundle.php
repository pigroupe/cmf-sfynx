<?php
/**
 * This file is part of the <CmfPluginsContent> project.
 *
 * @category   CmfPluginsContent
 * @package    Bunlde
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cmf\ContentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**************************  MATRIX LISTENER ***************************/
$GLOBALS['GEDMO_WIDGET_LISTENER']['PluginsContentBundle:BlocGeneral'] = array(
        'method' => array('_template_show', '_template_list', '_template_list_diapo', '_template_list_rubrique','_template_last_rubrique','get_content'),
        '_template_show'     => array(
                'edit'        => 'admin_content_blocgeneral_edit',
        ),
        '_template_list'     => array(
                'edit'        => 'admin_content_blocgeneral',
        ),
          '_template_list_diapo'     => array(
                'edit'        => 'admin_content_blocgeneral',
        ),
          '_template_last_rubrique'     => array(
                'edit'        => 'admin_content_blocgeneral',
        ),  
          '_template_list_rubrique'     => array(
                'edit'        => 'admin_content_blocgeneral',
        ),    
          'get_content'     => array(
                'edit'        => 'admin_content_blocgeneral',
        ),
);

$GLOBALS['GEDMO_WIDGET_LISTENER']['PluginsContentBundle:Article'] = array(
    'method' => array('_template_show', '_template_list', 'get_linked_contents', 'last_articles'),
    '_template_show'     => array(
        'edit'        => 'admin_content_article_edit',
    ),
    '_template_list'     => array(
        'edit'        => 'admin_content_article',
    ),
    'get_linked_contents'     => array(
        'edit'        => 'admin_content_article',
    ),
    'last_articles'     => array(
        'edit'        => 'admin_content_article',
    ),
);

$GLOBALS['GEDMO_WIDGET_LISTENER']['PluginsContentBundle:Diaporama'] = array(
    'method' => array('list_diaporama'),
    'list_diaporama'     => array(
        'edit'        => 'admin_content_diaporama',
    ),

);

$GLOBALS['GEDMO_WIDGET_LISTENER']['PluginsContentBundle:Tag'] = array(
    'method' => array('list'),
    'list'     => array(
        'edit'        => 'admin_content_tag_edit',
    ),
);



$GLOBALS['GEDMO_WIDGET_LISTENER']['PluginsContentBundle:Contents'] = array(
        'method' => array('_tmp_last_contents'),
);

$GLOBALS['GEDMO_WIDGET_LISTENER']['PluginsContentBundle:Page'] = array(
        'method' => array('_template_show', '_template_list'),
        '_template_show'     => array(
                'edit'        => 'admin_content_page_edit',
        ),
        '_template_list'     => array(
                'edit'        => 'admin_content_page',
        ),
);

$GLOBALS['GEDMO_WIDGET_LISTENER']['PluginsContentBundle:Test'] = array(
        'method' => array('_template_show', '_template_list', 'get_content'),
        '_template_show'     => array(
                'edit'        => 'admin_content_test_edit',
        ),
        '_template_list'     => array(
                'edit'        => 'admin_content_test',
        ),  
          'get_content'     => array(
                'edit'        => 'admin_content_test',
        ),    
);

$GLOBALS['GEDMO_WIDGET_LISTENER']['PluginsContentBundle:Rub'] = array(
        'method' => array('_template_show', '_template_list', '_template_infos', '_template_breadcrumb', '_template_entity_slug', 'get_children', 'get_sous_rubrique_content'),
        '_template_show'     => array(
                'edit'        => 'admin_content_rub_edit',
        ),
        '_template_list'     => array(
                'edit'        => 'admin_content_rub',
        ),
        '_template_infos'     => array(
                'edit'        => 'admin_content_rub_edit',
        ),  
          'get_children'     => array(
                'edit'        => 'admin_content_rub_edit',
        ),
          'get_sous_rubrique_content'     => array(
                'edit'        => 'admin_content_rub_edit',
        ),  
          '_template_breadcrumb'     => array(
                'edit'        => 'admin_content_rub',
        ),
          '_template_entity_slug'     => array(
                'edit'        => 'admin_content_rub',
        )  
);

/**************************  MATRIX NAVIGATION ***************************/
$GLOBALS['GEDMO_WIDGET_NAVIGATION']['PluginsContentBundle:Rub'] = array(
        'method' => array('_navigation_default'),
        '_navigation_default' => array(
                'edit'        => 'admin_content_rub_tree',
        )
);
$GLOBALS['GEDMO_WIDGET_ORGANIGRAM']['PluginsContentBundle:Rub'] = array(
        'method' => array('org-chart-page','org-tree-breadcrumb'),
        'org-chart-page' => array(
                        'edit'		=> 'admin_content_rub_tree',
        ),
        'org-tree-breadcrumb' => array(
                        'edit'		=> 'admin_content_rub_tree',
        )
);

/**
 * Gedmo managment Bundle
 *
 * @category   CmfPluginsContent
 * @package    Bunlde
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class CmfContentBundle extends Bundle
{
    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     *
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
    
    /**
     * Boots the Bundle.
     */
    public function boot()
    {
    }    
    
    /**
     * Shutdowns the Bundle.
     */
    public function shutdown()
    {
    }        
}
