<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   Admin_Twig
 * @package    Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cmf\ContentBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use PiApp\AdminBundle\Exception\ExtensionException;

/**
 * Tool Filters and Functions used in twig
 *
 * @category   plugins_Twig
 * @package    Extension
 * 
 * @author Riad Hellal <r.hellal@novediagroup.com>
 */
class PiToolExtension extends \Twig_Extension
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
     * @author Riad Hellal <r.hellal@novediagroup.com>
     */
    public function getName() {
        return 'plugins_tool_extension';
    }
        
        
    /**
     * Returns a list of filters to add to the existing list.
     * 
     * <code>
     *     {{ comment.content|html }}
     *  {{ 'pi.page.translation.title'|trans|limite('0', 25) }}
     *  {{ "%s Result"|translate_plural("%s Results", entitiesByMonth|count)}}
     * </code> 
     * 
     * @return array An array of filters
     * @access public
     *
     * @author Riad Hellal <r.hellal@novediagroup.com>
     * 
     */    
    public function getFilters() {
        return array(
          'urlContent'            => new \Twig_Filter_Method($this, 'urlContentFilter'),
          'typeContent'           => new \Twig_Filter_Method($this, 'typeContentFilter'),
        );
    }
    
    /**
     * $entity string
     *
     * @param string $string
     * 
     */
    public function urlContentFilter($entity)
    {
        if (!($entity instanceof \Cmf\ContentBundle\Entity\BlocGeneral)){
            return '';
        }        
        if ( !is_null($entity->getArticle())){ 
             $page = 'page_contenu_article' ;
        }elseif (!is_null($entity->getTest())){ 
             $page = 'page_content_test'  ;
        }elseif (!is_null($entity->getDiaporama())){ 
             $page = 'page_content_diaporama';
        }else{ 
             $page = 'page_content_page';     
        } 

        return $page;
    } 
    
    /**
     * $entity string
     *
     * @param string $string
     * 
     */
    public function typeContentFilter($entity)
    {
        if (!($entity instanceof \Cmf\ContentBundle\Entity\BlocGeneral)){
            return '';
        }
            
        if (method_exists($entity, 'getArticle') && (!is_null($entity->getArticle()))){ 
             $type = 'Article' ;
        }elseif (method_exists($entity, 'getTest') && (!is_null($entity->getTest()))){ 
             $type = 'Test'  ;
        }elseif (method_exists($entity, 'getDiaporama') && (!is_null($entity->getDiaporama()))){ 
             $type = 'Dossier';
        }elseif (method_exists($entity, 'getPage') && (!is_null($entity->getPage()))){ 
             $type = 'Page';
        }else{ 
             $type = '';                      
        } 

        return $type;
    } 
}