<?php
/**
 * This file is part of the <Tool> project.
 * 
 * @subpackage Tool
 * @package    Util
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2013-11-14
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Util;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\ToolBundle\Exception\ServiceException;
use Sfynx\ToolBundle\Route\AbstractFactory;
use Sfynx\ToolBundle\Util\PiFileManager;

/**
 * Script manager tool
 * 
 * @subpackage Tool
 * @package    Util
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiScriptManager extends AbstractFactory
{
    /**
     * Constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * Return a JS file in the container in links.
     *
     * @param string $content_js string   content js value
     * @param string $content_html string content html value
     * @param string $path_prefix string  prefix repository value
     * @param string $result              ['both', 'html', 'linkJs']
     * 
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function renderScript($content_js, $content_html, $path_prefix = '/', $result = "both")
    {
    	$TEMP_FILES_DIR = $this->getContainer()->getParameter("kernel.root_dir") . "/../web/yui/js/" . $path_prefix;
    	// we create repository if does not exit
    	PiFileManager::mkdirr($TEMP_FILES_DIR, 0777);
    	//  we create single file from all input
    	$input_hash = sha1($content_js);
    	$file       = $TEMP_FILES_DIR . $input_hash . '.js';
    	// we compress the content
    	if ( !file_exists($file) ) {
            $this->getContainer()
                    ->get('sfynx.tool.file_manager')
                    ->save($file, $content_js, 0777);
    	}
    	// we set result
    	$this->getContainer()
                ->get('sfynx.tool.twig.extension.layouthead')
                ->addJsFile("yui/js/".$input_hash. '.js');
    	$resultScript = '<script type="text/javascript" src="/yui/js/' . $path_prefix . $input_hash .'.js" ></script>';
    	// we return the result
    	if ($result == "both") {
            return $content_html . $resultScript;
    	} elseif ($result == "html") {
            return $content_html;
    	} elseif ($result == "linkJs") {
            return $resultScript;
    	} else {
            return $content_html .
    		"
            <script type='text/javascript'>
            //<![CDATA[
            ". $content_js . "
            //]]>
            </script>
            ";
    	}
    }  
}
