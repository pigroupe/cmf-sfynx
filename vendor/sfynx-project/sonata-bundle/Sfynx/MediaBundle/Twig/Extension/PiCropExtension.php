<?php
/**
 * This file is part of the <Library> project.
 *
 * @subpackage Library
 * @package    Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-01-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\MediaBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\ToolBundle\Util\PiFileManager;
use Sfynx\MediaBundle\Entity\Media;

/**
 * Tool Filters and Functions used in twig
 *
 * @subpackage Library
 * @package    Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiCropExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
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
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getName() {
        return 'sfynx_library_crop_extension';
    }
        
    /**
     * Returns a list of functions to add to the existing list.
     *  
     * <code>
     *  {{ media_url(id, 'default_small') }}
     * </code>
     * 
     * @return array An array of functions
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getFunctions() {
        return array(
            'file_form'     => new \Twig_Function_Method($this, 'getFileFormFunction'),
            'picture_form'  => new \Twig_Function_Method($this, 'getPictureFormFunction'),
            'picture_index' => new \Twig_Function_Method($this, 'getPictureIndexFunction'),
            'picture_crop'  => new \Twig_Function_Method($this, 'getPictureCropFunction'),
            'media_url'     => new \Twig_Function_Method($this, 'getMediaUrlFunction'),
        );
    }   
    
    /**
     * Functions
     */

    /**
     * display a file.
     * 
     * <code>
     * {% if entity.media.image is defined %}
	 *   {{ file_form(entity.image, "sfynx_mediabundle_mediatype_file_image_binaryContent",  'reference', 'display: block; text-align:left;')|raw }}
	 * {% endif %}
     * </code>
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getFileFormFunction($media, $nameForm, $style = "display: block; text-align:center;margin: 30px auto;z-index:99999999999", $is_by_mimetype = true)
    {
        if ($media instanceof Media){
            $id = $media->getId();
            try {
                    $file_url = $this->container->get('sonata.media.twig.extension')->path($id, "reference");
                    if ($is_by_mimetype){
                       $mime = str_replace('/','-',$media->getContentType());
                       $picto = '/bundles/sfynxtemplate/images/icons/mimetypes/'.$mime.'.png';
                    } else {
                        $ext = substr(strtolower(strrchr(basename($file_url), ".")), 1);
                        $picto = '/bundles/sfynxtemplate/images/icons/form/download-'.$ext.'.png';
                    }
                    if (!file_exists('.'.$picto)) {
                        $picto = '/bundles/sfynxtemplate/images/icons/form/download-32.png';
                    }
            } catch (\Exception $e) {
                return "";
            }
            $content     = "<div id='file_$id'> \n";
            $content    .= "<a href='{$file_url}' target='_blanc' style='{$style}'> <img src='$picto' /> ".$media->getName()." <br/> {$file_url}</a>";
            $content    .= "</div> \n";
            $content    .= "<script type='text/javascript'> \n";
            $content    .= "//<![CDATA[ \n";
            $content    .= "$('#file_$id').detach().appendTo('#{$nameForm}'); \n";
            $content    .= "//]]> \n";
            $content    .= "</script> \n";

            return $content;
        }
    }    

    /**
     * display a media.
     *
     * <code>
     * {% if entity.media.image is defined %}
     *   {{ picture_form(entity.media.image, "piapp_gedmobundle_blocktype_media_image_binaryContent",  'reference', 'display: block; text-align:left;')|raw }}
     * {% endif %}
     * </code>
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getPictureFormFunction($media, $nameForm, $format = 'reference', $style = "display: block; text-align:center;margin: 30px auto;", $idForm = "", $ClassName = '')
    {
    	if ($media instanceof Media) {
            $id = $media->getId();
            if ($format != 'reference') {
                $mediaCrop = $this->container->get('sonata.media.twig.extension')->path($media, $format);

                if(file_exists($src = $this->container->get('kernel')->getRootDir() . '/../web'.$mediaCrop)) {
                    $img_balise = '<img title="' . $media->getAuthorname() . '" src="' . $mediaCrop . '?' . time() . '" width="auto" height="auto" alt="' . $media->getAuthorname() . '" style="' . $style . '" >';
                } else {
                    $img_balise = $this->container->get('translator')->trans("pi.form.label.media.picture.no-format") . '<br/><br/>';
                }
                $content = "<div id='picture_" . $id . "_" . $format . "' class='".$format."  ".$ClassName."' > \n";
            } else {
                $img_balise = $this->container->get('sonata.media.twig.extension')->media($media, $format, array(
                    'title' => $media->getAuthorname(),
                    'alt' => $media->getAuthorname(),
                    'style' => $style,
                    'id' => $idForm,
                    'width' => 'auto',
                    'height' => 'auto'
                ));
                $content = "<div id='picture_" . $id . "_" . $format . "' class='".$format." ".$ClassName."' > \n";
            }
            $content .= $img_balise;
            $content .= "</div> \n";
            $content .= "<script type='text/javascript'> \n";
            $content .= "//<![CDATA[ \n";
            $content .= "$('#{$nameForm}').before($('#picture_" . $id . "_" . $format . "')); \n";
            $content .= "//]]> \n";
            $content .= "</script> \n";

            return $content;
    	}
    }    
    
    /**
     * crop a picture.
     *
     * <code>
     * {% if entity.media.image is defined %}
     *   {{ picture_crop(entity.media.image, "default", "piapp_gedmobundle_blocktype_media_image_binaryContent")|raw}}
     *   {{ picture_crop(entity.blocgeneral.media.image, "default", "plugins_contentbundle_articletype_blocgeneral_media", '', {'unset':[0,1]})|raw}}
     * {% endif %}
     * </code>
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getPictureCropFunction($media, $format = "SfynxTemplateBundle:Template\\Crop:default.html.twig", $nameForm = "", $type = '', $options = array())
    {
    	if ($format == "default") {
    		$format = "SfynxTemplateBundle:Template\\Crop:default.html.twig";
    	}
    	if ($media instanceof Media) {            
    		$crop     = $this->container->getParameter('sfynx.media.crop');
    		$globals  = $this->container->get('twig')->getGlobals();
            if (!empty($type) && (in_array($type, array('input', 'script')))) {
                $templateContent = $this->container->get('twig')->loadTemplate($format);
                $crop_input = ($templateContent->hasBlock("crop_input")
                      ? $templateContent->renderBlock("crop_input", array(
                          "media"=>$media,
                          "nameForm"=>$nameForm,
                          "crop" => $crop,
                      	  "options" => $options,
                      	  "globals" => $globals
                      ))
                      : "");
                $crop_script = ($templateContent->hasBlock("crop_script")
                      ? $templateContent->renderBlock("crop_script", array(
                          "media" =>$media,
                          "nameForm" =>$nameForm,
                          "crop" => $crop,
                      	  "options" => $options,
                      	  "globals" => $globals
                      ))
                      : "");  
                if ($type == 'input') {
                    return $crop_input;      
                } elseif ($type == 'script') {
                    return $crop_script;
                }              
            } else {
                $response = $this->container->get('templating')->renderResponse($format,array(
                    "media"=>$media,
                    "nameForm"=>$nameForm,
                    "crop" => $crop,
                    "options" => $options,
                    "globals" => $globals
                ));

                return $response->getContent();
            }
    	}
    }   

    /**
     * show a crop picture.
     *
     * <code>
     * {% if entity.media.image is defined %}
     *   {{ picture_index(entity.media.image, 'slider', slider_width ,  slider_height )|raw }}
     * {% endif %}
     * </code>
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function getPictureIndexFunction($media, $format = '', $width='', $height='')
    {
        if ($media instanceof Media) {
            $id = $media->getId();
            $mediaCrop = $this->container->get('sonata.media.twig.extension')->path($media, $format);
            if(file_exists($src = $this->container->get('kernel')->getRootDir() . '/../web'.$mediaCrop)) {
                $img_balise = '<img title="' . $media->getAuthorname() . '" src="' . $mediaCrop . '?' . time() . '" width="auto" height="auto" alt="' . $media->getAuthorname() . '"/>';
            } else {
                $img_balise = 'Aucune image ce format ';
            }
            $content ="<div>Dimensions de ".$format." = " .$width."x".$height."</div>";
            $content .= "<div id='picture_" . $id . $format . "' class='".$format." default_crop' > \n";
            $content .= $img_balise;
            $content .= "</div></br></br> \n";
            
            return $content;
        }
    }
    
    /**
     * Creating a link.
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function linkFunction( $label, $path, $options = array() ) {
        $attributes = '';
        foreach ( $options as $key=>$value ) {
            $attributes .= ' ' . $key . '="' . $value . '"';
        }

        return '<a href="' . $path . '"' . $attributes . '>' . $label . '</a>';
    }
    
    /**
     * Return the $returnTrue value if the route of the page is include in $paths value, else return the $returnFalse value.
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function inPathsFunction($paths, $returnTrue = '', $returnFalse = '')
    {
        $route = (string) $this->container->get('request')->get('_route');
        $names = explode(':', $paths);
        $is_true = false;        
        if (is_array($names)) {
            foreach ($names as $k => $path) {
                if ($route == $path)
                    $is_true = true;
            }
            if ($is_true) {
                return $returnTrue;
            } else {
                return $returnFalse;
            }            
        } else {
            if ($route == $paths) {
                return $returnTrue;
            } else {
                return $returnFalse;
            }            
        }
    }    
    
    /**
     * Return the url of a media (and put the result in cache).
     *
     * @param string $id
     * @param string $format   ["default_small", "default_big", "reference"]
     * @param string $cachable
     *
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function getMediaUrlFunction($id, $format = "small", $cachable = true, $modifdate = false, $pattern = "media_")
    {
        if ($modifdate instanceof \Datetime) {
            $timestamp = $modifdate->getTimestamp();
        } elseif(is_string($modifdate)) {
            $timestamp = $modifdate;
        } else {
        	$timestamp = 0;
        }        
        try {
            if (!$cachable){
                $url_public_media = $this->container->get('sonata.media.twig.extension')->path($id, $format) . "?v.$timestamp";
            } else {
                $dossier = $this->container->getParameter("sfynx.core.cache_dir.media");
                PiFileManager::mkdirr($dossier, 0777);
                $this->container->get("sfynx.cache.filecache")->getClient()->setPath($dossier);
                $url_public_media = $this->container->get("sfynx.cache.filecache")->get($format.$pattern.$id.'_'.$timestamp);        
                if (!$url_public_media) {
                    $url_public_media = $this->container->get('sonata.media.twig.extension')->path($id, $format) . "?v.$timestamp";
                    $this->container->get("sfynx.cache.filecache")->set($format.$pattern.$id.'_'.$timestamp, $url_public_media, 0);
                }       
            }     
        } catch (\Exception $e) {
            $url_public_media = "";
        }
        $src = $this->container->get('kernel')->getRootDir() . '/../web' . $url_public_media;
        if ((empty($url_public_media) || !file_exists($src)) && ($format != 'reference')) {
            return $this->getMediaUrlFunction($id, "reference", $cachable, $modifdate, $pattern);
        } else {
            return $url_public_media;
        }
    }    
}
