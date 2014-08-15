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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use PiApp\AdminBundle\Manager\PiFormBuilderManager;
use PiApp\GedmoBundle\Manager\FormBuilder\PiModelWidgetSlideCollectionType;
use PiApp\GedmoBundle\Manager\FormBuilder\PiModelWidgetSearchFieldsType;
        
/**
* Description of the Form builder manager
*
* @category   Gedmo_Managers
* @package    FormBuilder
*
* @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
*/
class PiModelWidgetOrganigram extends PiFormBuilderManager
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
        parent::__construct($containerService, 'WIDGET', 'organigram', $this::FORM_TYPE_NAME, $this::FORM_DECORATOR, $this::FORM_NAME);
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
                PiFormBuilderManager::CONTENT_RENDER_TITLE  => "Widget Organigram",
                PiFormBuilderManager::CONTENT_RENDER_DESC   => "call for inserting an organigram",
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
        $ListsAvailableEntities = \PiApp\AdminBundle\Util\PiWidget\PiGedmoManager::getAvailableOrganigram();
        $ListsAvailableEntities = array_combine(array_keys($ListsAvailableEntities), array_keys($ListsAvailableEntities));
        // we get all slide templates
        $listFiles = $this->container->get('pi_app_admin.file_manager')->ListFilesBundle("/Resources/views/Template/Organigram");
        $listFiles = array_map(function($value) {
        	return basename($value);
        }, array_values($listFiles));
        $listFiles = array_combine($listFiles, $listFiles);
        //
        $css = array();
        // actions
        $actions = array(
                'org-chart-page' => 'Organigram par défault',
                'org-tree-semantique' => 'Arbre sémantique',
        );
        // we create the forme
        $builder
        ->add('action', 'choice', array(
        		'choices'   => $actions,
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
        ->add('template', 'choice', array(
        		'choices'   => $listFiles,
        		'multiple'    => false,
        		'required'  => true,
                'expanded' => false,
        		'label' => 'pi.form.label.select.choose.template',
        		"attr" => array(
        				"class"=>"pi_simpleselect",
        		),
        ))   
        ->add('css', 'choice', array(
        		'choices'   => $css,
        		'multiple'    => true,
        		'required'  => false,
        		'expanded' => false,
        		'label' => 'pi.form.label.select.choose.css',
        		"attr" => array(
        				"class"=>"pi_multiselect",
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
        ->add('category', 'text', array(
        		'required'  => false,
        		'label'    => "Catégorie",
        		"label_attr" => array(
        				"class"=>"text_collection",
        		),
        ))     
        ->add('node', 'text', array(
        		'required'  => false,
        		'label'    => "Noeud",
        		"label_attr" => array(
        				"class"=>"text_collection",
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
        ->add('query_function', 'text', array(
                'required'  => false,
        		'label'    => "Nom de la fonction SQL",
        		"label_attr" => array(
        				"class"=>"text_collection",
        		),
        ))   
        ->add('organigramsearchfields', 'collection', array(
        		'allow_add' => true,
        		'allow_delete' => true,
        		'prototype'    => true,
        		// Post update
        		'by_reference' => true,
        		'type'   => new PiModelWidgetSearchFieldsType($this->_locale, $this->_container),
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
            &nbsp;&nbsp;&nbsp;<a href="#" id="add-another-sqlparameters-organigram">Add another field SQL</a>
            <script type="text/javascript">
            //<![CDATA[            
                jQuery(document).ready(function() {
                    var indexSQLParams    = 0;
                    jQuery("div#piappgedmobundlemanagerformbuilderpimodelwidgetorganigram").find("fieldset").append('<br ><ul id="sqlparams-fields-list-organigram" ></ul>');
                    jQuery('#add-another-sqlparameters-organigram').click(function() {
                        var prototypeList = jQuery('#prototype_script_organigramsearchfields');   
                        // parcourt le template prototype
                        var newWidget2 = prototypeList.html().replace('<label class="required">__name__label__</label>', '');
                        // remplace les "__name__" utilisés dans l'id et le nom du prototype
                        // par un nombre unique pour chaque email
                        // le nom de l'attribut final ressemblera à name="contact[emails][2]"
                        newWidget2 = newWidget2.replace(/__name__/g, indexSQLParams);
                        indexSQLParams++;            
                        // créer une nouvelle liste d'éléments et l'ajoute à notre liste
                        var newLi2 = jQuery('<li class="addcollection"></li>').html(newWidget2);
                        newLi2.appendTo(jQuery('#sqlparams-fields-list-organigram'));
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
                'action'    => 'organigram',
                'xml'         => Array (
                        "widgets"     => Array (
                                'css' => $AllCss,
                                "gedmo"        => Array (
                                        "controller"        => $data['table'].':'.$data['action'],
                                        "params"    => array(
                                            'template' => $data['template'],
                                            'enabledonly' => $data['enabled'],
                                            'category' => $data['category'],
                                            'node' => $data['node'],
                                            'organigram' => array(
                                                'query_function'  => $data['query_function'],
                                                'searchFields' => $data['navigationsearchfields'],
                                            )
                                        )
                                )
                        )
                ),
        );
    }        

}
