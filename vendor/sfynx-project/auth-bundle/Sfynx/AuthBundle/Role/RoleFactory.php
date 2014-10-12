<?php
/**
 * This file is part of the <Auth> project.
 *
 * @subpackage   User
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\Role;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;

use Sfynx\ToolBundle\Route\AbstractFactory;
use Sfynx\AuthBundle\Builder\RoleFactoryInterface;

use BeSimple\I18nRoutingBundle\Routing\Generator\UrlGenerator;
use BeSimple\I18nRoutingBundle\Routing\I18nRoute;

/**
 * role factory.
 *
 * @subpackage   User
 * @package    Manager
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class RoleFactory extends AbstractFactory implements RoleFactoryInterface
{
    protected $path_json_file;
    
    /**
     * Constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->path_json_file = $container->getParameter("kernel.cache_dir") . "/../heritage.json";
    }
    
    /**
     * Gets all no authorize roles of an heritage of roles.
     *
     * @param array     $heritage
     * @return array    the best roles of all roles.
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getNoAuthorizeRoles($heritage)
    {
    	if ( is_null($heritage) || (count($heritage) == 0) ) {
    		return null;
    	}
    	$all_roles              = array_keys($this->getContainer()->getParameter('security.role_hierarchy.roles'));
      	if (($key = array_search('ROLE_ALLOWED_TO_SWITCH', $all_roles)) !== false) {
       		unset($all_roles[$key]);
       	}
       	$all_roles_authorized    = array_merge($heritage, $this->getAllHeritageByRoles($heritage));
       	$all_roles_no_authorized = array_diff($all_roles, $all_roles_authorized);
       	//
       	if ( (count($all_roles_authorized) == 0) && (count($all_roles_no_authorized) == 0) ) {
       		return null;
       	}
       	//
       	$all_roles_authorized = array_map(function($value) {
            return "is_granted('{$value}')";
        },array_values($all_roles_authorized));
       	$script_or = implode(' or ', $all_roles_authorized);
       	//
       	$all_roles_no_authorized = array_map(function($value) {
       		return "not is_granted('{$value}')";
       	},array_values($all_roles_no_authorized));
       	$script_and = implode(' and ', $all_roles_no_authorized);
       	//
       	if (!empty($script_or) && !empty($script_and)) {
       		$twig_if      = "{{ \" {% if ({$script_or}) and $script_and  %} \" }}\n";
       	} elseif (empty($script_or) && !empty($script_and)) {
       		$twig_if      = "{{ \" {% if $script_and  %} \" }}\n";
       	} elseif (!empty($script_or) && empty($script_and)) {
       		$twig_if      = "{{ \" {% if ({$script_or}) %} \" }}\n";
       	}
       	$twig_endif   = "{{ \" {% endif %}  \" }} \n";
    
    	return array(
    			'autorized' => $all_roles_authorized,
    			'no_authorized' => $all_roles_no_authorized,
    			'script_or' => $script_or,
    			'script_and' => $script_and,
    			'twig_if' => $twig_if,
    			'twig_endif' => $twig_endif
    	);
    }    
    
    /**
     * Gets all user roles.
     *
     * @param array     $ROLES
     * @return array    the best roles of all roles.
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getAllUserRoles()
    {
        if ($this->isUsernamePasswordToken()) {
            return array_unique(array_merge($this->getAllHeritageByRoles($this->getBestRoles($this->getUserRoles())), $this->getUserRoles()));
        } else {
            return null;
        }
    }   
    
    /**
     * Return false if the json file does not existe
     *
     * @return boolean
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function isJsonFileExisted()
    {
    	return realpath($this->path_json_file);
    }    

    /**
     * Create the json heritage file with all roles information.
     *
     * @return boolean
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function setJsonFileRoles()
    {
    	// we register the hierarchy roles in the heritage.jon file in the cache
    	$em         = $this->getContainer()->get('doctrine')->getManager();
        $roles      = $em->getRepository('SfynxAuthBundle:role')->getAllHeritageRoles();
        // we delete cache files
        $path_files[] = realpath($this->getContainer()->getParameter("kernel.cache_dir") . "/appDevDebugProjectContainer.php");
        $path_files[] = realpath($this->getContainer()->getParameter("kernel.cache_dir") . "/appDevDebugProjectContainer.php.meta");
        $path_files[] = realpath($this->getContainer()->getParameter("kernel.cache_dir") . "/appDevDebugProjectContainer.xml");
        $path_files[] = realpath($this->getContainer()->getParameter("kernel.cache_dir") . "/appDevDebugProjectContainerCompiler.log");
        $path_files[] = realpath($this->getContainer()->getParameter("kernel.cache_dir") . "/appProdProjectContainer.php");
        $path_files = array_unique($path_files);
        foreach ($path_files as $key=>$file) {
        	if (!empty($file)) {
        		unlink($file);
        	}
        }        
        
        return file_put_contents($this->path_json_file, json_encode(array('HERITAGE_ROLES'=>$roles), JSON_UNESCAPED_UNICODE));  
    }    
    
    /**
     * Gets the best role of all user roles.
     *
     * @return string    the best role of all user roles.
     * @access protected
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getBestRoleUser()
    {
    	if ($this->isUsernamePasswordToken()) {
	    	// we get all user roles.
	    	$ROLES_USER    = $this->getUserRoles();
	    	// we get the map of all roles.
	    	$roleMap = $this->buildRoleMap();
	    	foreach ($roleMap as $role => $heritage) {
	    		if (in_array($role, $ROLES_USER)) {
	    			$intersect    = array_intersect($heritage, $ROLES_USER);
	    			$ROLES_USER    = array_diff($ROLES_USER, $intersect);  // =  $ROLES_USER -  $intersect
	    		}
	    	}
	    
	    	return end($ROLES_USER);
    	} else {
    		return '';
    	}
    }    
    
    /**
     * Gets the best roles of many of roles.
     *
     * @param array     $ROLES
     * @return array    the best roles of all roles.
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getBestRoles($ROLES)
    {
	     if (is_null($ROLES)) {
             return null;
         }
         // we get the map of all roles.
         $roleMap = $this->buildRoleMap();
         foreach ($roleMap as $role => $heritage) {
             if (in_array($role, $ROLES)){
                 $intersect    = array_intersect($heritage, $ROLES);
                 $ROLES        = array_diff($ROLES, $intersect);  // =  $ROLES_USER -  $intersect
             }
         }
            
         return $ROLES;
    }
    
    /**
     * Gets all heritage roles of many of roles.
     *
     * @param array     $ROLES
     * @return array    the best roles of all user roles.
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getAllHeritageByRoles($ROLES)
    {
	    if (is_null($ROLES)) {
            return null;
        }
        $results = array();
        // we get the map of all roles.
        $roleMap = $this->buildRoleMap();
        foreach ($ROLES as $key => $role) {
            if (isset($roleMap[$role]))
                $results = array_unique(array_merge($results, $roleMap[$role]));
        }
        
        return $results;
    }
    
    /**
     * Sets the map of all roles.
     *
     * @return array    role map
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function buildRoleMap()
    {
        $hierarchy     = $this->getContainer()->getParameter('security.role_hierarchy.roles');
        $map        = array();
        foreach ($hierarchy as $main => $roles) {
            $map[$main] = $roles;
            $visited = array();
            $additionalRoles = $roles;
            while ($role = array_shift($additionalRoles)) {
                if (!isset($hierarchy[$role])) {
                    continue;
                }

                $visited[]          = $role;
                $map[$main]      = array_unique(array_merge($map[$main], $hierarchy[$role]));
                $additionalRoles = array_merge($additionalRoles, array_diff($hierarchy[$role], $visited));
            }
            if (($key = array_search($main, $map[$main])) !== false) {
                unset($map[$main][$key]);
            }
        }
        return $map;
    }
}