<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Cmf
 * @package    Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\ToolBundle\Exception\ExtensionException;
use Sfynx\ToolBundle\Exception\ServiceException;

/**
 * Tool Filters and Functions used in twig
 *
 * @subpackage   Cmf
 * @package    Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiSeoExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    
    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
        
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getName() {
        return 'sfynx_cmf_seo_extension';
    }
        
    /**
     * Returns a list of functions to add to the existing list.
     * 
     * @return array An array of functions
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getFunctions() {
        return array(
                'metas_page' => new \Twig_Function_Method($this, 'getMetaPageFunction'),
                'title_page' => new \Twig_Function_Method($this, 'getTitlePageFunction'),
        );
    }   
    
    /**
     * Functions
     */

    /**
     * Return the meta title of a page.
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getTitlePageFunction($lang, $title)
    {
    	if (empty($title)) {
    		$title  = $this->container->getParameter('pi_app_admin.layout.meta.title');
    	}
    	$options 		  = $this->container->get('pi_app_admin.manager.page')->getPageMetaInfo($lang, $title);
    	$options['title'] = str_replace(array('"',"'"), array("’","’"), $options['title']);
    	return $options['title'];
    }
    
    /**
     * Return the metas of a page.
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getMetaPageFunction($lang, array $options)
    {
    	// we get the param.
    	if (empty($lang)) {
    		$lang            = $this->container->get('request')->getLocale();
    	}
    	$Uri             = $this->container->get('request')->getUri();
    	$BasePath        = $this->container->get('request')->getUriForPath('');
    	$author          = str_replace(array('"',"'"), array("’","’"), $this->container->getParameter('pi_app_admin.layout.meta.author'));
    	$copyright       = str_replace(array('"',"'"), array("’","’"), $this->container->getParameter('pi_app_admin.layout.meta.copyright'));
    	$description     = str_replace(array('"',"'"), array("’","’"), $this->container->getParameter('pi_app_admin.layout.meta.description'));
    	$keywords        = str_replace(array('"',"'"), array("’","’"), $this->container->getParameter('pi_app_admin.layout.meta.keywords'));
    	$og_title_add    = str_replace(array('"',"'"), array("’","’"), $this->container->getParameter('pi_app_admin.layout.meta.og_title_add'));
    	$og_type         = str_replace(array('"',"'"), array("’","’"), $this->container->getParameter('pi_app_admin.layout.meta.og_type'));
    	$og_image        = str_replace(array('"',"'"), array("’","’"), $this->container->getParameter('pi_app_admin.layout.meta.og_image'));
    	$og_site_name    = str_replace(array('"',"'"), array("’","’"), $this->container->getParameter('pi_app_admin.layout.meta.og_site_name'));
        // if the file doesn't exist, we call an exception
        $og_image        = strip_tags($this->container->get('translator')->trans($og_image));
        $is_file_exist   = realpath($this->container->get('kernel')->getRootDir(). '/../web/' . $og_image);
        if (!$is_file_exist) {
            throw ExtensionException::FileUnDefined('img',__CLASS__);
        }        
        $og_image = $this->container->get('templating.helper.assets')->getUrl($og_image);
        //
        if (isset($options['title']) && !empty($options['title'])) {
        	$title = $options['title'];
        }
        if (isset($options['description']) && !empty($options['description'])) {
        	$description = $options['description'];
        }        
        if (isset($options['keywords']) && !empty($options['keywords'])) {
        	$keywords = $options['keywords'];
        }        
        // we get all info of a the current page.
        $options = $this->container->get('pi_app_admin.manager.page')->getPageMetaInfo($lang, $title, $description, $keywords);
        // we create the copyright link
        if (isset($copyright) && !empty($copyright)) {
        	$copyright = strip_tags($this->container->get('translator')->trans($copyright));
        	$metas[] = "<link rel='copyright' href=\"".$copyright."\"/>";
        }
        // we create all meta tags.
        $metas[] = "    <meta charset='".$this->container->get('twig')->getCharset()."'/>";
        $metas[] = "    <meta http-equiv='Content-Type'/>";
        $metas[] = "    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'/>";
        $metas[] = "    <meta name='generator' content=\"Sfynx\"/>";
        //
        if (isset($author) && !empty($author)) {
            $author = strip_tags($this->container->get('translator')->trans($author));
        	$metas[] = "    <meta name='author' content=\"".$author."\"/>";
        }
        if (isset($options['description']) && !empty($options['description'])) {
            $metas[] = "    <meta name='description' content=\"".$options['description']."\"/>";
        }
        if (isset($options['keywords']) && !empty($options['keywords'])) {
            $metas[] = "    <meta name='keywords' content=\"".$options['keywords']."\"/>";
        }
        $metas[] = "    <meta property='og:url' content=\"{$Uri}\"/>";
        //
        if (isset($options['title']) && !empty($options['title'])) {
            $metas[] = "    <meta property='og:title' content=\"{$og_title_add}{$options['title']}\"/>";
        }
        if (isset($og_type) && !empty($og_type)) {
            $og_type = strip_tags($this->container->get('translator')->trans($og_type));
            $metas[] = "    <meta property='og:type' content=\"{$og_type}\"/>";
        }
        if (isset($og_image) && !empty($og_image)) {
            $og_image = strip_tags($this->container->get('translator')->trans($og_image));
            $metas[] = "    <meta property='og:image' content=\"{$BasePath}{$og_image}\"/>";
        }
        if (isset($og_site_name) && !empty($og_site_name)) {
            $og_site_name = strip_tags($this->container->get('translator')->trans($og_site_name));
            $og_site_name = str_replace('https://', '', $og_site_name);
            $og_site_name = str_replace('http://', '', $og_site_name);
            $metas[]      = "    <meta property='og:site_name' content=\"{$og_site_name}\"/>";
        }
        // additions management
        $additions       = $this->container->getParameter('pi_app_admin.layout.meta.additions');
        ksort($additions);
        foreach ($additions as $k => $values) {
        	$metas[] = $values;
        }
        
        return implode(" \n", $metas);
    }    
    
    
}