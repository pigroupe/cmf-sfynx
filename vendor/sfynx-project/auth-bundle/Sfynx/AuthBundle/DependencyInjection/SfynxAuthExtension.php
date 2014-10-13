<?php
/**
 * This file is part of the <Auth> project.
 *
 * @subpackage   Auth
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader,
    Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @subpackage   Auth
 * @package    Configuration
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SfynxAuthExtension extends Extension{

    public function load(array $config, ContainerBuilder $container)
    {
        // we load all services
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services_cmfconfig.yml');
        $loader->load('mail_blacklist.yml');
        $loader->load('services.yml');
        // we load config
        $configuration = new Configuration();
        $config  = $this->processConfiguration($configuration, $config);
        
        /**
         * Locale config parameter
         */
        if (isset($config['locale']['authorized'])) {
        	$container->setParameter('sfynx.auth.locale.authorized', $config['locale']['authorized']);
        } else {
        	$container->setParameter('sfynx.auth.locale.authorized', array());
        }    

        /**
         * Browser config parameter
         */
        if (isset($config['browser'])){
        	if (isset($config['browser']['switch_language_authorized'])) {
        		$container->setParameter('sfynx.auth.browser.switch_language_authorized', $config['browser']['switch_language_authorized']);
        	}
        	if (isset($config['browser']['switch_layout_mobile_authorized'])) {
        		$container->setParameter('sfynx.auth.browser.switch_layout_mobile_authorized', $config['browser']['switch_layout_mobile_authorized']);
        	}
        }           
        
        /**
         * Redirection login config
         */
        if (isset($config['default_login_redirection'])){
        	if (isset($config['default_login_redirection']['redirection'])) {
        		$container->setParameter('sfynx.auth.login.redirect', $config['default_login_redirection']['redirection']);
        	}
        	if (isset($config['default_login_redirection']['template'])) {
        		$container->setParameter('sfynx.auth.login.template', $config['default_login_redirection']['template']);
        	}
        }
        
        /**
         * Layout config parameter
         */        
        if (isset($config['default_layout'])){
        	if (isset($config['default_layout']['init_pc'])){
        		if (isset($config['default_layout']['init_pc']['template'])) {
        			$container->setParameter('sfynx.auth.layout.init.pc.template', $config['default_layout']['init_pc']['template']);
        		}
        	}        
        	if (isset($config['default_layout']['init_mobile'])){
        		if (isset($config['default_layout']['init_mobile']['template'])) {
        			$container->setParameter('sfynx.auth.layout.init.mobile.template', $config['default_layout']['init_mobile']['template']);
        		}
        	}
        }    

        /**
         * Theme config parameter
         */
        if (isset($config['theme'])){
        	if (isset($config['theme']['name'])) {
        		$container->setParameter('sfynx.auth.theme.name', strtolower($config['theme']['name']));
        	}
        	if (isset($config['theme']['login'])) {
        	    $container->setParameter('sfynx.auth.theme.login', $config['theme']['login']);  // "SfynxSmoothnessBundle::Login\\"
        	}
        	if (isset($config['theme']['layout'])) {
        	    $container->setParameter('sfynx.auth.theme.layout', $config['theme']['layout']); // "SfynxSmoothnessBundle::Layout\\"
        	}
        	if (isset($config['theme']['global']['layout'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.global', $config['theme']['global']['layout']); // "SfynxSmoothnessBundle::Layout\\layout-global-cmf.html.twig"
        	}
        	if (isset($config['theme']['global']['css'])) {
        		$container->setParameter('sfynx.auth.theme.layout.global.css', $config['theme']['global']['css']); // "SfynxSmoothnessBundle::Layout\\layout-global-cmf.html.twig"
        	}        	
        	if (isset($config['theme']['ajax']['layout'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.ajax', $config['theme']['ajax']['layout']); // "SfynxSmoothnessBundle::Layout\\layout-ajax.html.twig"
        	}
        	if (isset($config['theme']['ajax']['css'])) {
        		$container->setParameter('sfynx.auth.theme.layout.ajax.css', $config['theme']['ajax']['css']); // "SfynxSmoothnessBundle::Layout\\layout-ajax.html.twig"
        	}        	
        	if (isset($config['theme']['error']['route_name'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.error.route_name', $config['theme']['error']['route_name']);  // "error_404"
        	}
        	if (isset($config['theme']['error']['html'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.error.html', $config['theme']['error']['html']); // "@SfynxSmoothnessBundle/Resources/views/Error/error.html.twig"
        	}
        	if (isset($config['theme']['admin']['pc'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.admin.pc', $config['theme']['admin']['pc']); // "SfynxSmoothnessBundle::Layout\\Pc\\"
        	}
        	if (isset($config['theme']['admin']['mobile'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.admin.mobile', $config['theme']['admin']['mobile']); // "SfynxSmoothnessBundle::Layout\\Mobile\\Admin\\"
        	}
        	if (isset($config['theme']['admin']['grid']['img'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.admin.grid.img', $config['theme']['admin']['grid']['img']); // "/bundles/sfynxsmoothness/admin/grid/"
        	}
        	if (isset($config['theme']['admin']['grid']['css'])) {
        		$container->setParameter('sfynx.auth.theme.layout.admin.grid.css', $config['theme']['admin']['grid']['css']); // "/bundles/sfynxsmoothness/admin/grid/"
        	}   
           	if (isset($config['theme']['admin']['grid']['type'])) {
        		$container->setParameter('sfynx.auth.theme.layout.admin.grid.type', $config['theme']['admin']['grid']['type']); 
        	}
            if (isset($config['theme']['admin']['grid']['state_save'])) {
        		$container->setParameter('sfynx.auth.theme.layout.admin.grid.state.save', $config['theme']['admin']['grid']['state_save']); 
        	}
        	if (isset($config['theme']['admin']['grid']['row_select'])) {
        		$container->setParameter('sfynx.auth.theme.layout.admin.grid.row.select', $config['theme']['admin']['grid']['row_select']); 
        	}        	        	 	
        	if (isset($config['theme']['admin']['grid']['pagination'])) {
        		$container->setParameter('sfynx.auth.theme.layout.admin.grid.pagination', $config['theme']['admin']['grid']['pagination']);
        	}
        	if (isset($config['theme']['admin']['grid']['pagination_type'])) {
        		$container->setParameter('sfynx.auth.theme.layout.admin.grid.pagination.type', $config['theme']['admin']['grid']['pagination_type']); 
        	}        	
        	if (isset($config['theme']['admin']['grid']['pagination_top'])) {
        		$container->setParameter('sfynx.auth.theme.layout.admin.grid.pagination.top', $config['theme']['admin']['grid']['pagination_top']); 
        	}        	
        	if (isset($config['theme']['admin']['grid']['lengthmenu'])) {
        		$container->setParameter('sfynx.auth.theme.layout.admin.grid.lengthmenu', $config['theme']['admin']['grid']['lengthmenu']); 
        	}        	
        	if (isset($config['theme']['admin']['grid']['filters_tfoot_up'])) {
        		$container->setParameter('sfynx.auth.theme.layout.admin.grid.filters.tfoot.up', $config['theme']['admin']['grid']['filters_tfoot_up']); 
        	}
        	if (isset($config['theme']['admin']['grid']['filters_active'])) {
        		$container->setParameter('sfynx.auth.theme.layout.admin.grid.filters.active', $config['theme']['admin']['grid']['filters_active']);
        	}        	        	
        	if (isset($config['theme']['admin']['form']['builder'])) {
       			$container->setParameter('sfynx.auth.theme.layout.admin.form.builder', $config['theme']['admin']['form']['builder']);
        	}   
            if (isset($config['theme']['admin']['form']['template'])) {
       			$container->setParameter('sfynx.auth.theme.layout.admin.form.template', $config['theme']['admin']['form']['template']);
        	}   
        	if (isset($config['theme']['admin']['form']['css'])) {
        		$container->setParameter('sfynx.auth.theme.layout.admin.form.css', $config['theme']['admin']['form']['css']);
        	}       	     	
        	if (isset($config['theme']['admin']['flash'])) {
        		$container->setParameter('sfynx.auth.theme.layout.admin.flash', $config['theme']['admin']['flash']);
        	}        	
        	if (isset($config['theme']['admin']['css'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.admin.css', $config['theme']['admin']['css']); // 'bundles/sfynxsmoothness/admin/screen.css'
        	}
        	if (isset($config['theme']['admin']['home'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.admin.home', $config['theme']['admin']['home']);  // 'SfynxSmoothnessBundle:Home:admin.html.twig'
        	}
        	if (isset($config['theme']['admin']['dashboard'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.admin.dashboard', $config['theme']['admin']['dashboard']); // 'dashboard.default.html.twig'
        	}
        	if (isset($config['theme']['front']['pc'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.front.pc', $config['theme']['front']['pc']); // "SfynxSmoothnessBundle::Layout\\Pc\\"
        	}
        	if (isset($config['theme']['front']['pc_path'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.front.pc.path', $config['theme']['front']['pc_path']); // "@SfynxSmoothnessBundle/Resources/views/Layout/Pc/"
        	}
        	if (isset($config['theme']['front']['mobile'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.front.mobile', $config['theme']['front']['mobile']);  // "SfynxSmoothnessBundle::Layout\\Mobile\\"
        	}
        	if (isset($config['theme']['front']['mobile_path'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.front.mobile.path', $config['theme']['front']['mobile_path']);  // "@SfynxSmoothnessBundle/Resources/views/Layout/Mobile/"
        	}
        	if (isset($config['theme']['front']['css'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.front.css', $config['theme']['front']['css']);  // 'bundles/sfynxsmoothness/front/screen.css'
        	}
        	if (isset($config['theme']['connexion']['login'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.connexion.login', $config['theme']['connexion']['login']);  // "SfynxSmoothnessBundle::Login\\Security\\login-layout.html.twig"
        	}
        	if (isset($config['theme']['connexion']['widget'])) {
        	    $container->setParameter('sfynx.auth.theme.layout.connexion.widget', $config['theme']['connexion']['widget']);  // "SfynxSmoothnessBundle::Login\\Security\\connexion-widget.html.twig"
        	}
        }        
    }
    
    public function getAlias()
    {
    	return 'sfynx_auth';
    }    

}
