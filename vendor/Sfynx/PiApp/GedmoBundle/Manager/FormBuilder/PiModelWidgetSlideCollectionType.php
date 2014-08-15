<?php
/**
 * This file is part of the <Gedmo> project.
 *
 * @category   Gedmo_Managers
 * @package    FormBuilder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2014-08-31
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\GedmoBundle\Manager\FormBuilder;  

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of the Form builder manager
 *
 * @category   Gedmo_Managers
 * @package    FormBuilder
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiModelWidgetSlideCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('field', 'choice', array(
        		'choices'   => array(
        		        
        		    "Default" => array(
        		        'namespace'=>'namespace',
        		        'selector'=>'selector',
        		        'animation'=>'animation, "fade" or "slide"',
        		        'easing'=>'easing, ex: "swing"',
        		        'direction'=>'direction, "horizontal" or "vertical"',
        		        'reverse'=>'reverse, true or false',
        		        'animationLoop'=>'animationLoop, true or false',
        		        'smoothHeight'=>'smoothHeight, true or false',
        		        'startAt'=>'startAt, ex: 0',
        		        'slideshow'=>'slideshow, true or false',
        		        'slideshowSpeed'=>'slideshowSpeed, ex: 7000',
        		        'animationSpeed'=>'animationSpeed, ex: 800',
        		        'initDelay'=>'initDelay, ex: 0',
        		        'randomize'=>'randomize, true or false',
                    ),    
        		    'Usability features' => array(
        		        'pauseOnAction'=>'pauseOnAction, true or false',
        		        'pauseOnHover'=>'pauseOnHover, true or false',
        		        'useCSS'=>'useCSS, true or false',
        		        'touch'=>'touch, true or false',
        		        'video'=>'video, true or false',
        		    ),
        		    "Primary Controls" => array(
        		        'controlNav'=>'controlNav, "thumbnails" or true or false',
        		        'directionNav'=>'directionNav, true or false',
        		        'prevText'=>'prevText, ex: "Previous"',
        		        'nextText'=>'nextText, ex: "Next"',
        		    ),
        		    "Secondary Navigation" => array(
        		        'keyboard'=>'keyboard, true or false',
        		        'multipleKeyboard'=>'multipleKeyboard, true or false',
        		        'mousewheel'=>'mousewheel, true or false',
        		        'pausePlay'=>'pausePlay, true or false',
        		        'pauseText'=>'pauseText, ex: "Pause"',
        		        'playText'=>'playText, ex: "Play"',
        		    ),
        		    "Special properties" => array(
        		        'controlsContainer'=>'controlsContainer',
        		        'manualControls'=>'manualControls',
        		        'sync'=>'sync',
        		        'asNavFor'=>'asNavFor',
        		    ),   
        		    "Carousel Option" => array(
        		        'itemWidth'=>'itemWidth',
        		        'itemMargin'=>'itemMargin',
        		        'minItems'=>'minItems, ex: 1',
        		        'maxItems'=>'maxItems, ex: 1',
        		        'move'=>'move',
        		     )
        		),
        		'required'  => true,
        		'multiple'    => false,
        		'expanded' => false,
        		'label'    => "Parameter",
        		"label_attr" => array(
        				"class"=>"select_choice",
        		),
        		"attr" => array(
        				"class"=>"pi_simpleselect",
        		),
        ));
        $builder->add('value', 'text', array(
                'label'    => "Value",
        ));
        
    }

    public function getName()
    {
        return 'piapp_adminbundle_enquirytype';
    }
}