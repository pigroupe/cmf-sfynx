<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Cmf
 * @package    Jquery
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-29
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Util\PiJquery;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Sfynx\ToolBundle\Twig\Extension\PiJqueryExtension;
use Sfynx\ToolBundle\Exception\ExtensionException;

/**
 * Backstretch Jquery plugin
 *
 * @subpackage   Cmf
 * @package    Jquery
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiBackstretchManager extends PiJqueryExtension
{
    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }
    
    /**
     * Sets init.
     *
     * @access protected
     * @return void
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     */    
    protected function init($options = null) {
        // js
        $this->container->get('sfynx.tool.twig.extension.layouthead')->addJsFile("bundles/sfynxtemplate/js/jquery/jquery.backstretch.min.js");
    }    
    
    /**
      * Set progress text for Progress flash dialog.
      *
      * <code>
      *        {% set options_backstretch = {'id': 'body', 'img': 'bundles/sfynxtemplate/images/layout/novedia/BACKGROUNDS/background-home.jpg' } %}
      *        {{ renderJquery('TOOL', 'backstretch', options_backstretch )|raw }}    
      * </code>
      * 
      * @param    $options    tableau d'options.
      * @access protected
      * @return void
      *
      * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com> 
      */
    protected function render($options = null)
    {        
        // Options management
        if (!isset($options['id']) || empty($options['id'])) {
            throw ExtensionException::optionValueNotSpecified('id', __CLASS__);
        }
        if (!isset($options['img']) || empty($options['img'])) {
            throw ExtensionException::optionValueNotSpecified('img', __CLASS__);
        }    
        // if the file doesn't exist, we call an exception
        $is_file_exist = realpath($this->container->get('kernel')->getRootDir(). '/../web/' . $options['img']);
        if (!$is_file_exist) {
            throw ExtensionException::FileUnDefined('img', __CLASS__);
        }
        $Urlpath = $this->container->get('templating.helper.assets')->getUrl($options['img']);       
        // We open the buffer.
        ob_start ();        
        ?>
                $.backstretch("<?php echo $Urlpath; ?>");
        <?php 
        // We retrieve the contents of the buffer.
        $_content_js = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();
                
        return  $this->renderScript($_content_js, '', 'cmf/backstretch/');
    }    
}