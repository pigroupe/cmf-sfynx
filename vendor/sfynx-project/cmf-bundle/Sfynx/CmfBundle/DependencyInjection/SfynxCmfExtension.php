<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @category   Cmf
 * @package    DependencyInjection
 * @subpackage Extension
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
namespace Sfynx\CmfBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader,
    Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @category   Cmf
 * @package    DependencyInjection
 * @subpackage Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class SfynxCmfExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        // we load all services
        $loaderYaml  = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/service'));
        $loaderYaml->load('services_cmfconfig.yml');
        $loaderYaml->load('services_util.yml');
        $loaderYaml->load('services_twig_extension.yml');
        $loaderYaml->load('services.yml');
        $loaderYaml->load("services_form_builder.yml");
        $loaderYaml->load("services_form_validator.yml");
        $loaderYaml->load('services_listener.yml');        
        // we load config
        $configuration = new Configuration();
        $config  = $this->processConfiguration($configuration, $config);
        
        /**
         * Cache config parameter
         */
        if (isset($config['cache_dir'])){
            if (isset($config['cache_dir']['etag'])) {
                $container->setParameter('pi_app_admin.cache_dir.etag', $config['cache_dir']['etag']);
            }
            if (isset($config['cache_dir']['indexation'])) {
                $container->setParameter('pi_app_admin.cache_dir.indexation', $config['cache_dir']['indexation']);
            }      
            if (isset($config['cache_dir']['widget'])) {
                $container->setParameter('pi_app_admin.cache_dir.widget', $config['cache_dir']['widget']);
            }             
            if (isset($config['cache_dir']['seo'])) {
            	$container->setParameter('pi_app_admin.seo.redirection.repository', $config['cache_dir']['seo']);
            }            
        }            

        /**
         * Admin config parameter
         */
        if (isset($config['admin'])){
            if (isset($config['admin']['context_menu_theme'])) {
                $container->setParameter('pi_app_admin.admin.context_menu_theme', $config['admin']['context_menu_theme']);
            }
        }        
        
        /**
         * Page config parameter
         */
        if (isset($config['page'])){
            if (isset($config['page']['homepage_deletewidget'])) {
                $container->setParameter('pi_app_admin.page.homepage_deletewidget', $config['page']['homepage_deletewidget']);
            }
            if (isset($config['page']['page_management_by_user_only'])) {
                $container->setParameter('pi_app_admin.page.management_by_user_only', $config['page']['page_management_by_user_only']);
            }
            //            
            if (isset($config['page']['route']) && isset($config['page']['route']['with_prefix_locale'])) {
            	$container->setParameter('pi_app_admin.page.route.with_prefix_locale', $config['page']['route']['with_prefix_locale']);
            }          
            if (isset($config['page']['route']) && isset($config['page']['route']['single_slug'])) {
            	$container->setParameter('pi_app_admin.page.route.single_slug', $config['page']['route']['single_slug']);
            }
            //
            if (isset($config['page']['esi']) && isset($config['page']['esi']['authorized'])) {
            	$container->setParameter('pi_app_admin.page.esi.authorized', $config['page']['esi']['authorized']);
            }
            if (isset($config['page']['esi']) && isset($config['page']['esi']['encrypt_key'])) {
            	$container->setParameter('pi_app_admin.page.esi.encrypt_key', $config['page']['esi']['encrypt_key']);
            }
            if (isset($config['page']['esi']) && isset($config['page']['esi']['force_widget_tag_esi_for_varnish'])) {
            	$container->setParameter('pi_app_admin.page.esi.force_widget_tag_esi_for_varnish', $config['page']['esi']['force_widget_tag_esi_for_varnish']);
            }
            if (isset($config['page']['esi']) && isset($config['page']['esi']['force_private_response_for_all'])) {
            	$container->setParameter('pi_app_admin.page.esi.force_private_response_for_all', $config['page']['esi']['force_private_response_for_all']);
            } 
            if (isset($config['page']['esi']) && isset($config['page']['esi']['force_private_response_only_with_authentication'])) {
            	$container->setParameter('pi_app_admin.page.esi.force_private_response_only_with_authentication', $config['page']['esi']['force_private_response_only_with_authentication']);
            } 
            if (isset($config['page']['esi']) && isset($config['page']['esi']['disable_after_post_request'])) {
            	$container->setParameter('pi_app_admin.page.esi.disable_after_post_request', $config['page']['esi']['disable_after_post_request']);
            }       
            //
            if (isset($config['page']['widget']) && isset($config['page']['widget']['render_service_with_ttl'])) {
            	$container->setParameter('pi_app_admin.page.widget.render_service_with_ttl', $config['page']['widget']['render_service_with_ttl']);
            }
            if (isset($config['page']['widget']) && isset($config['page']['widget']['render_service_with_ajax'])) {
            	$container->setParameter('pi_app_admin.page.widget.render_service_with_ajax', $config['page']['widget']['render_service_with_ajax']);
            }      
            if (isset($config['page']['widget']) && isset($config['page']['widget']['ajax_disable_after_post_request'])) {
            	$container->setParameter('pi_app_admin.page.widget.ajax_disable_after_post_request', $config['page']['widget']['ajax_disable_after_post_request']);
            }
            //
            if (isset($config['page']['scop']) && isset($config['page']['scop']['authorized'])) {
            	$container->setParameter('pi_app_admin.page.scop.authorized', $config['page']['scop']['authorized']);
            }
            if (isset($config['page']['scop']) && isset($config['page']['scop']['globals'])) {
            	$container->setParameter('pi_app_admin.page.scop.globals', $config['page']['scop']['globals']);
            }  
            //                                  
            if (isset($config['page']['refresh']) && isset($config['page']['refresh']['allpage'])) {
            	$container->setParameter('pi_app_admin.page.refresh.allpage', $config['page']['refresh']['allpage']);
            }
            if (isset($config['page']['refresh']) && isset($config['page']['refresh']['allpage_containing_snippet'])) {
            	$container->setParameter('pi_app_admin.page.refresh.allpage_containing_snippet', $config['page']['refresh']['allpage_containing_snippet']);
            }
            if (isset($config['page']['refresh']) && isset($config['page']['refresh']['css_js_cache_file'])) {
            	$container->setParameter('pi_app_admin.page.refresh.css_js_cache_file', $config['page']['refresh']['css_js_cache_file']);
            }                        
            //
            if (isset($config['page']['indexation_authorized_automatically'])) {
                $container->setParameter('pi_app_admin.page.indexation_authorized_automatically', $config['page']['indexation_authorized_automatically']);
            }
            if (isset($config['page']['memcache_enable_all']))  {
            	$container->setParameter('pi_app_admin.page.memcache_enable_all', $config['page']['memcache_enable_all']);
            }
        }
        
        /**
         * Seo config parameter
         */
        if (isset($config['seo'])){
            if (isset($config['seo']['meta_head'])) {
                foreach ($config['seo']['meta_head'] as $k => $v) {
                	$container->setParameter('pi_app_admin.layout.meta.' . $k, $v);
                }
            }  
            if (isset($config['seo']['redirection_oldurl_to_new_url']) && isset($config['seo']['redirection_oldurl_to_new_url']['authorized'])) {
            	$container->setParameter('pi_app_admin.seo.redirection.authorized', $config['seo']['redirection_oldurl_to_new_url']['authorized']);
            }
            if (isset($config['seo']['redirection_oldurl_to_new_url']) && isset($config['seo']['redirection_oldurl_to_new_url']['file_name'])) {
            	$container->setParameter('pi_app_admin.seo.redirection.file_name', $config['seo']['redirection_oldurl_to_new_url']['file_name']);
            }            
        }          
    
    }
  
    public function getAlias()
    {
        return 'sfynx_cmf';
    }   
}
