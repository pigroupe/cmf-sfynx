<?php
/**
 * This file is part of the <Gedmo> project.
 *
 * @category   Gedmo_Managers
 * @package    Page
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2014-08-31
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\GedmoBundle\Manager\FormBuilder;  

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use PiApp\AdminBundle\Manager\PiFormBuilderManager;
use PiApp\GedmoBundle\Manager\FormBuilder\PiModelWidgetSlideCollectionType;
use PiApp\GedmoBundle\Manager\FormBuilder\PiModelWidgetSlideCollection2Type;
        
/**
* Description of the Form builder manager
*
* @category   Gedmo_Managers
* @package    Page
*
* @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
*/
class PiModelWidgetSlide extends PiFormBuilderManager
{
    /**
     * Type form name.
     */
    const FORM_TYPE_NAME = 'symfony';
    
    /**
     * Default decorator file name
     */
    const FORM_DECORATOR = 'model_form_builder.html.twig';    
    
    /**
     * Form name.
     */
    const FORM_NAME = 'formbuilder';    
        
    /**
     * Constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface
     * 
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function __construct(ContainerInterface $containerService)
    {
        parent::__construct($containerService, 'WIDGET', 'slide', $this::FORM_TYPE_NAME, $this::FORM_DECORATOR, $this::FORM_NAME);
    }
    
    /**
     * Return list of available content types for all type pages.
     *
     * @param  array    $options
     * @return array
     * @access public
     * @static
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-06-27
     */
    public static function getContents()
    {
        return array(
                PiFormBuilderManager::CONTENT_RENDER_TITLE    => "Widget Slide",
                PiFormBuilderManager::CONTENT_RENDER_DESC   => "call for inserting a slider",
        );
    }    

    /**
     * Chargement du template de formulaire.
     *
     * @access protected
     * @return string
     *
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-09-11
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // we get all entities
        //$listTableClasses = $this->container->get('bootstrap.database.db')->listTables('table_class');
        //$listTableClasses = array_combine($listTableClasses, $listTableClasses);
        $ListsAvailableEntities = \PiApp\AdminBundle\Util\PiWidget\PiGedmoManager::getAvailableSlider();
        $ListsAvailableEntities = array_combine(array_keys($ListsAvailableEntities), array_keys($ListsAvailableEntities));
        // we get all slide templates
        $listFiles = $this->container->get('pi_app_admin.file_manager')->ListFilesBundle("/Resources/views/Template/Slider");
        $listFiles = array_map(function($value) {
        	return basename($value);
        }, array_values($listFiles));
        $listFiles = array_combine($listFiles, $listFiles);
        //
        $action = \PiApp\GedmoBundle\Util\PiJquery\PiFlexSliderManager::$actions;
        $action = array_combine($action, $action);
        //
        $menus = \PiApp\GedmoBundle\Util\PiJquery\PiFlexSliderManager::$menus;
        $menus = array_combine($menus, $menus);    
        //
        $css = array(
            "bundles/piappadmin/js/slider/flexslider/css/flexslider_v1.css" => 'Default',
            "bundles/piappadmin/js/slider/flexslider/css/flexslider_v2.css" => 'Default-2',
            "bundles/piappadmin/js/slider/flexslider/css/flexslider_v3.css" => 'Default-3',
        );
        // we create the forme
        $builder
        ->add('action', 'choice', array(
                'choices'   => $action,
                'required'  => true,
                'multiple'    => false,
                'expanded' => false,
                'label'    => "Action",
                "label_attr" => array(
                        "class"=>"select_choice",
                ),
                "attr" => array(
                		"class"=>"pi_simpleselect",
                ),
        ))
        ->add('menu', 'choice', array(
        		'choices'   => $menus,
        		'required'  => true,
        		'multiple'    => false,
        		'expanded' => false,
                'preferred_choices' => array('entity'),
        		'label'    => "Menu",
        		"label_attr" => array(
        				"class"=>"select_choice",
        		),
                "attr" => array(
                		"class"=>"pi_simpleselect",
                ),
        ))        
        ->add('template', 'choice', array(
        		'choices'   => $listFiles,
        		'multiple'    => false,
        		'required'  => true,
                'expanded' => false,
                'preferred_choices' => array('default.html.twig'),
        		'label' => 'pi.form.label.select.choose.template',
        		"attr" => array(
        				"class"=>"pi_simpleselect",
        		),
        ))   
        ->add('css', 'choice', array(
        		'choices'   => $css,
        		'multiple'    => true,
        		'required'  => true,
        		'expanded' => false,
        		'preferred_choices' => array("bundles/piappadmin/js/slider/flexslider/css/flexslider_v1.css"),
        		'label' => 'pi.form.label.select.choose.css',
        		"attr" => array(
        				"class"=>"pi_multiselect",
        		),
        ))   
        ->add('id', 'text', array(
        		'required'  => false,
        		'label'    => "Identifiant",
        		"label_attr" => array(
        				"class"=>"text_collection",
        		),
        ))             
        ->add('class', 'text', array(
                'required'  => false,
                'data' => "flexslider",
        		'label'    => "Classe Css",
        		"label_attr" => array(
        				"class"=>"text_collection",
        		),
        		'help_block' => 'ex: flexslider',
        )) 
        ->add('height', 'text', array(
        		'required'  => false,
        		'label'    => "Hauteur",
        		"label_attr" => array(
        				"class"=>"text_collection",
        		),
        		'help_block' => 'ex: 100px, 95%',
        ))   
        ->add('width', 'text', array(
        		'required'  => false,
        		'label'    => "Largeur",
        		"label_attr" => array(
        				"class"=>"text_collection",
        		),
        		'help_block' => 'ex: 100px, 95%',
        ))   
        ->add('insert_js', 'choice', array(
        		'choices'   => array(1=>'pi.form.label.field.yes', 0=>'pi.form.label.field.no'),
        		'required'  => true,
        		'multiple'    => false,
        		'expanded' => false,
        		'preferred_choices' => array(1),
        		'label'    => "Insérer code JS",
        		"label_attr" => array(
        				"class"=>"text_collection",
        		),
        ))                    
        ->add('table', 'choice', array(
        		'choices'   => $ListsAvailableEntities,
        		'multiple'    => false,
        		'required'  => true,
        		'expanded' => false,
        		'empty_value' => 'Choice a table',
        		"attr" => array(
        				"class"=>"pi_simpleselect",
        		),
        		"label_attr" => array(
        				"class"=>"select_choice",
        		),
        		"attr" => array(
        				"class"=>"pi_simpleselect",
        		),
        ))        
        ->add('enabled', 'choice', array(
        		'choices'   => array(1=>'pi.form.label.field.yes', 0=>'pi.form.label.field.no'),
        		'required'  => false,
        		'multiple'    => false,
        		'expanded' => false,
        		'label'    => "Activer",
        		"label_attr" => array(
        				"class"=>"select_choice",
        		),
        		"attr" => array(
        				"class"=>"pi_simpleselect",
        		),
        ))
        ->add('orderby_position', 'choice', array(
        		'choices'  => array('ASC'=>'ASC', 'DESC'=>'DESC'),
        		'required' => false,
        		'multiple' => false,
        		'expanded' => false,
        		'label'    => "Classer par position",
        		"label_attr" => array(
        	        "class"=>"select_choice",
        		),
        		"attr" => array(
        		    "class"=>"pi_simpleselect",
        		),
        ))
        ->add('orderby_date', 'choice', array(
        		'choices'  => array('ASC'=>'ASC', 'DESC'=>'DESC'),
        		'required' => false,
        		'multiple' => false,
        		'expanded' => false,
        		'label'    => "Classer par date de publication",
        		"label_attr" => array(
        				"class"=>"select_choice",
        		),
        		"attr" => array(
        				"class"=>"pi_simpleselect",
        		),
        ))     
        ->add('MaxResults', 'text', array(
        		'label'    => "Nbr de resultat Max",
        		"label_attr" => array(
        				"class"=>"text_collection",
        		),
        ))
        ->add('query_function', 'text', array(
                'required'  => false,
        		'label'    => "Nom de la fonction SQL",
        		"label_attr" => array(
        				"class"=>"text_collection",
        		),
        ))   
        ->add('boucle_array', 'choice', array(
        		'choices'   => array(1=>'pi.form.label.field.yes', 0=>'pi.form.label.field.no'),
        		'required'  => true,
        		'multiple'    => false,
        		'expanded' => false,
        		'preferred_choices' => array(0),
        		'label'    => "Résultat boucles sous forme Tableau",
        		"label_attr" => array(
        				"class"=>"select_choice",
        		),
        		"attr" => array(
        				"class"=>"pi_simpleselect",
        		),
        ))   
        ->add('flexsliderparams', 'collection', array(
        		'allow_add' => true,
        		'allow_delete' => true,
        		'prototype'    => true,
        		// Post update
        		'by_reference' => true,
        		'type'   => new PiModelWidgetSlideCollectionType($this->_locale, $this->_container),
        		'options'  => array(
        				'attr'      => array('class' => 'collection_widget')
        		),
        		'label'    => ' '
        ))
        ->add('searchfields', 'collection', array(
        		'allow_add' => true,
        		'allow_delete' => true,
        		'prototype'    => true,
        		// Post update
        		'by_reference' => true,
        		'type'   => new PiModelWidgetSlideCollection2Type($this->_locale, $this->_container),
        		'options'  => array(
        				'attr'      => array('class' => 'collection_widget')
        		),
        		'label'    => ' '
        ))
        ;
    }
    
    /**
     * Sets JS script.
     *
     * @param    array $options
     * @access public
     * @return void
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function renderScript(array $option) 
    {
        // We open the buffer.
        ob_start ();
        ?>
            <br/>
            &nbsp;&nbsp;&nbsp;<a href="#" id="add-another-slideparameters">Add another parameter</a>
            &nbsp;&nbsp;&nbsp;<a href="#" id="add-another-sqlparameters">Add another field SQL</a>
            &nbsp;&nbsp;&nbsp;<a href="bundles/piappadmin/js/slider/flexslider/options.txt" onclick="javascript:window.open(this.href, 'sfynx_licence', 'scrollbars=yes,resizable=yes,width=740,height=630'); return false;">Options</a>
            <script type="text/javascript">
            //<![CDATA[            
                jQuery(document).ready(function() {
                	var indexFlexsliderParams    = 0;
                	jQuery("div#piappgedmobundlemanagerformbuilderpimodelwidgetslide").find("fieldset").append('<ul id="flexsliderparams-fields-list" ></ul>');
                    jQuery('#add-another-slideparameters').click(function() {
                        var prototypeList = jQuery('#prototype_script_flexsliderparams');   
                        // parcourt le template prototype
                        var newWidget = prototypeList.html().replace('<label class="required">__name__label__</label>', '');
                        // remplace les "__name__" utilisés dans l'id et le nom du prototype
                        // par un nombre unique pour chaque email
                        // le nom de l'attribut final ressemblera à name="contact[emails][2]"
                        newWidget = newWidget.replace(/__name__/g, indexFlexsliderParams);
                        indexFlexsliderParams++;            
                        // créer une nouvelle liste d'éléments et l'ajoute à notre liste
                        var newLi = jQuery('<li class="addcollection"></li>').html(newWidget);
                        newLi.appendTo(jQuery('#flexsliderparams-fields-list'));
                        // we align the fields
                        return false;
                    });

                    var indexSQLParams    = 0;
                    jQuery("div#piappgedmobundlemanagerformbuilderpimodelwidgetslide").find("fieldset").append('<br ><ul id="sqlparams-fields-list" ></ul>');
                    jQuery('#add-another-sqlparameters').click(function() {
                        var prototypeList = jQuery('#prototype_script_searchfields');   
                        // parcourt le template prototype
                        var newWidget2 = prototypeList.html().replace('<label class="required">__name__label__</label>', '');
                        // remplace les "__name__" utilisés dans l'id et le nom du prototype
                        // par un nombre unique pour chaque email
                        // le nom de l'attribut final ressemblera à name="contact[emails][2]"
                        newWidget2 = newWidget2.replace(/__name__/g, indexSQLParams);
                        indexSQLParams++;            
                        // créer une nouvelle liste d'éléments et l'ajoute à notre liste
                        var newLi2 = jQuery('<li class="addcollection"></li>').html(newWidget2);
                        newLi2.appendTo(jQuery('#sqlparams-fields-list'));
                        // we align the fields
                        return false;
                    });
                })            
            //]]>
            </script>                      
        <?php 
        // We retrieve the contents of the buffer.
        $_content = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();
        
        return $_content;        
    }        
    
    /**
     *
     *
     * @access public
     * @return void
     *
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-09-11
     */
    public function preEventBindRequest(){}    
    
    /**
     *
     *
     * @access public
     * @return void
     *
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-09-11
     */
    public function preEventActionForm(array $data){
    }

    /**
     *
     *
     * @access public
     * @return void
     *
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-09-11
     */
    public function postEventActionForm(array $data){}    
    
    /**
     *
     *
     * @access public
     * @return array        Xml config in array format to create/update a widget.
     *
     * @author (c) Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-09-11
     */
    public function XmlConfigWidget(array $data)
    {
        $AllCss = array();
        foreach ($data['css'] as $css) {
            $AllCss[] = $css;
        }
        
        return
        array(
                'plugin'    => 'gedmo',
                'action'    => 'slider',
                'xml'         => Array (
                        "widgets"     => Array (
                                'css' => $AllCss,
                                "gedmo"        => Array (
                                        "controller"        => $data['table'].':slide-default',
                                        "params"    => array(
                                            'template' => $data['template'],
                                            'slider' => array(
                                                'action' => $data['action'],
                                                'menu'   => $data['menu'],
                                                'id'  => $data['id'],
                                                'class'  => $data['class'],
                                                'width'  => $data['width'],
                                                'height'  => $data['height'],
                                                'enabled'  => $data['enabled'],
                                                'insert_js'  => $data['insert_js'],
                                                'orderby_date'  => $data['orderby_date'],
                                                'orderby_position'  => $data['orderby_position'],
                                                'MaxResults'  => $data['MaxResults'],
                                                'boucle_array'  => $data['boucle_array'],
                                                'query_function'  => $data['query_function'],
                                                'searchFields' => array(
                                                        0 => array('nameField'    => '','valueField'   => ''),
                                                        1 => array('nameField'    => '','valueField'   => '')
                                                )
                                            )
                                        )
                                )
                        )
                ),
        );
    }        

}
