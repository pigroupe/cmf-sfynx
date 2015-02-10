<?php
/**
 * This file is part of the <Media> project.
 *
 * @category PI_CRUD_Form
 * @package  Form
 * @author   Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since    20XX-XX-XX
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

use PiApp\GedmoBundle\Entity\Category;

/**
 * Description of the MediaType form.
 *
 * @category Media
 * @package  Form
 * @author   Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class MediathequeType extends AbstractType
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;
    
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $_container;    
    
    /**
     * @var string
     */
    protected $_status;    
    
    /**
     * @var string
     */
    protected $_class;    
    
    /**
     * @var string
     */
    protected $_simpleLink;    
    
    /**
     * @var string
     */
    protected $_labelLink;    

    /**
     * @var string
     */
    protected $_context;
    
    /**
     * Constructor.
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param string $status    ['file', 'image', 'youtube', 'dailymotion']
     * @return void
     */
    public function __construct(ContainerInterface $container, EntityManager $em, $status = "image", $class =  "media_collection", $simpleLink = "all", $labelLink = "", $context = "")
    {
        $this->_em           = $em;
        $this->_container    = $container;
        $this->_status       = $status;
        $this->_class        = $class;
        $this->_simpleLink   = $simpleLink;
        $this->_labelLink    = $labelLink;
        $this->_context      = $context;
    }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id_category = null;
        if ($builder->getData()->getCategory()
                instanceof Category
        ) {
            $id_category = $builder->getData()->getCategory()->getId();
        }
        if (isset($_POST['sfynx_mediabundle_mediatype_image']['category'])) {
            $id_category = $_POST['sfynx_mediabundle_mediatype_image']['category'];
        }  
        //
        $builder
                ->add('status', 'hidden', array(
                    "data"        => $this->_status,
                    "label_attr" => array(
                            "class"=> $this->_class,
                    ),
                    'required'  => false,
                ))
                ->add('updated_at',null,array(
                		'attr'=>array('style'=>'display:none;'),
                		"label_attr" => array(
                				"style"=> 'display:none;',
                		),
                ))                
        ;
        
        if ($this->_simpleLink == "all"){
            $builder            
                 ->add('enabled', 'checkbox', array(
                        //'data'  => true,
                         'label'    => 'pi.form.label.field.enabled',
                         "label_attr" => array(
                                 "class"=> $this->_class,
                         ),
                ))           
                 ->add('category', 'entity', array(
                    'class' => 'PiAppGedmoBundle:Category',
                    'query_builder' => function(EntityRepository $er) {
                        $translatableListener = $this->_container->get('gedmo.listener.translatable');
                        $translatableListener->setTranslationFallback(true);
                        return $er->createQueryBuilder('k')
                        ->select('k')
                        ->where('k.type = :type')
                        ->orderBy('k.name', 'ASC')
                        ->setParameter('type', 2);
                    },
                    'property' => 'name',
                    'empty_value' => 'pi.form.label.select.choose.category',
                    'label'    => "pi.form.label.field.category",
                    'multiple'    => false,
                    'required'  => false,
                    "attr" => array(
                        "class"=>"pi_simpleselect ajaxselect", // ajaxselect
                        "data-url"=>$this->_container->get('sfynx.tool.route.factory')->getRoute("admin_gedmo_category_selectentity_ajax", array('type'=> 2)),
                        "data-selectid" => $id_category,
                        "data-max" => 50,
                    ),
                    'widget_suffix' => '<a class="button-ui-mediatheque button-ui-dialog"
                                    title="Ajouter une catégorie"
                                    data-title="Catégorie"
                                    data-href="'.$this->_container->get('sfynx.tool.route.factory')->getRoute("admin_gedmo_category_new", array("NoLayout"=>"false", 'type'=> 2)).'"
                                    data-selectid="#piapp_gedmobundle_categorytype_id"
                                    data-selecttitle="#piapp_gedmobundle_categorytype_name"
                                    data-insertid="#sfynx_mediabundle_mediatype_image_category"
                                    data-inserttype="multiselect"
                                    ></a>',  
                ))               
                 ->add('title', 'text', array(
                         'label'            => "pi.form.label.field.title",
                         "label_attr"     => array(
                                 "class"=> $this->_class,
                         ),
                         'required'      => true,
                         'constraints' => array(
                         		new Constraints\NotBlank(),
                         ),                         
                 ))  
                 ->add('descriptif', 'textarea', array(
                 		'label'    => 'pi.form.label.field.description',
                 		"label_attr" => array(
                 				"class"=>"content_collection",
                 		),
                 ))                           
                 ->add('url', 'text', array(
                         "label"     => "pi.form.label.field.url",
                         "label_attr" => array(
                                 "class"=> $this->_class,
                         ),
                         'required'  => false,
                 ))                     
            ;
        }elseif ($this->_simpleLink == "simpleCategory"){
            $builder
                ->add('enabled', 'hidden', array(
                        'data'  => true,
                         "label_attr" => array(
                                 "class"=> $this->_class,
                         ),
                ))   
                ->add('category', 'entity', array(
                    'class' => 'PiAppGedmoBundle:Category',
                    'query_builder' => function(EntityRepository $er) {
                        $translatableListener = $this->_container->get('gedmo.listener.translatable');
                        $translatableListener->setTranslationFallback(true);
                        return $er->createQueryBuilder('k')
                        ->select('k')
                        ->where('k.type = :type')
                        ->orderBy('k.name', 'ASC')
                        ->setParameter('type', 4);
                    },
                    'property' => 'name',
                    'empty_value' => 'pi.form.label.select.choose.category',
                    'label'    => "pi.form.label.field.category",
                    'multiple'    => false,
                    'required'  => false,
                    "attr" => array(
                        "class"=>"pi_simpleselect ajaxselect", // ajaxselect
                        "data-url"=>$this->_container->get('sfynx.tool.route.factory')->getRoute("admin_gedmo_category_selectentity_ajax", array('type'=> 4)),
                        "data-selectid" => $id_category,
                        "data-max" => 50,
                    ),
                    'widget_suffix' => '<a class="button-ui-mediatheque button-ui-dialog"
                                    title="Ajouter une catégorie"
                                    data-title="Catégorie"
                                    data-href="'.$this->_container->get('sfynx.tool.route.factory')->getRoute("admin_gedmo_category_new", array("NoLayout"=>"false", 'type'=> 4)).'"
                                    data-selectid="#piapp_gedmobundle_categorytype_id"
                                    data-selecttitle="#piapp_gedmobundle_categorytype_name"
                                    data-insertid="#piapp_gedmobundle_slidertype_category"
                                    data-inserttype="multiselect"
                                    ></a>',  
                ))
            ; 
        } elseif ( ($this->_simpleLink == "simpleDescriptif") || ($this->_simpleLink == "simpleWithIcon") ) {
        	$builder
        	->add('enabled', 'hidden', array(
        			'data'  => true,
        			"label_attr" => array(
        					"class"=> $this->_class,
        			),
        	))
        	->add('title', 'text', array(
        			'label'            => "pi.form.label.field.title",
        			"label_attr"     => array(
        					"class"=> $this->_class,
        			),
        			'required'      => true,
        	        'constraints' => array(
        	        		new Constraints\NotBlank(),
        	        ),        	        
        	))
        	->add('descriptif', 'textarea', array(
        			'label'    => 'pi.form.label.field.description',
        			"label_attr" => array(
        					"class"=>"content_collection",
        			),
        	))
        	;
        } elseif ($this->_simpleLink == "crop"){
        	$builder
        	->add('enabled', 'hidden', array(
        			'data'  => true,
        			"label_attr" => array(
        					"class"=> $this->_class,
        			),
        	))        	
        	->add('title', 'text', array(
        			'label'            => "pi.form.label.field.title",
        			"label_attr"     => array(
        					"class"=> $this->_class,
        			),
        			'required'      => true,
        	        'constraints' => array(
        	        		new Constraints\NotBlank(),
        	        ),        	        
        	))
        	;                   
        } elseif ( ($this->_simpleLink == "simpleLink") || ($this->_simpleLink == "hidden") || ($this->_simpleLink == "simple") ){
            $builder
            ->add('enabled', 'hidden', array(
                        'data'  => true,
                         "label_attr" => array(
                                 "class"=> $this->_class,
                         ),
                ))
            ;
        }
        if ($this->_simpleLink == "hidden") {
            $style = "display:none";
        } else {
            $style = "";
        }
        if ($this->_status == "file") {
            if ($this->_labelLink == "")    $this->_labelLink    = 'pi.form.label.media.file';
            if ($this->_context == "")    $this->_context        = 'default';
             $builder->add('image', 'sonata_media_type', array(
                     'provider'  => 'sonata.media.provider.file',
                     'context'   => $this->_context,
                     'label'        => $this->_labelLink,
                     "label_attr" => array(
                             "class"=> $this->_class,
                             "style"=> $style,
                     ),
                     "attr"    => array("style"=> $style),
                     'required'  => false,
             ));        
         } elseif ($this->_status == "image") {
             if ($this->_labelLink == "") $this->_labelLink = 'pi.form.label.media.picture';     
             if ($this->_context == "")    $this->_context        = 'default';
             $builder->add('image', 'sonata_media_type', array(
                     'provider'     => 'sonata.media.provider.image',
                     'context'      => $this->_context,
                     'label'        => $this->_labelLink,
                     "label_attr" => array(
                             "class"=> $this->_class,
                             "style"=> $style,
                     ),
                     "attr"    => array("style"=> $style),
                     'required'  => false,
             ));   
             if ($this->_simpleLink == "simpleWithIcon"){
             	if ($this->_labelLink == "") $this->_labelLink = 'miniature';
             	if ($this->_context == "")    $this->_context        = 'default';
             	$builder->add('image2', 'sonata_media_type', array(
             			'provider'     => 'sonata.media.provider.image',
             			'context'      => $this->_context,
             			'label'        => "pi.form.label.media.picture.miniature",
             			"label_attr" => array(
             					"class"=> $this->_class,
             					"style"=> $style,
             			),
             			"attr"    => array("style"=> $style),
             			'required'  => false,
             	));
             }         
         } elseif ($this->_status == "youtube") {
             if ($this->_labelLink == "") $this->_labelLink     = 'pi.form.label.media.youtube';
             if ($this->_context == "")    $this->_context        = 'default';
             $builder->add('image', 'sonata_media_type', array(
                     'provider'     => 'sonata.media.provider.youtube',
                     'context'      => $this->_context,
                     'label'        => $this->_labelLink,
                     "label_attr" => array(
                             "class"=> $this->_class,
                             "style"=> $style,
                     ),
                     "attr"    => array("style"=> $style),
                     'required'  => false,
             ));
         }elseif ($this->_status == "dailymotion"){
             if ($this->_labelLink == "") $this->_labelLink     = 'pi.form.label.media.dailymotion';
             if ($this->_context == "")    $this->_context        = 'default';
             $builder->add('image', 'sonata_media_type', array(
                     'provider'     => 'sonata.media.provider.dailymotion',
                     'context'      => $this->_context,
                     'label'        => $this->_labelLink,
                     "label_attr" => array(
                             "class"=> $this->_class,
                             "style"=> $style,
                     ),
                     "attr"    => array("style"=> $style),
                     'required'  => false,
             ));
         }     


         if (($this->_simpleLink != "hidden") && ($this->_simpleLink != "simple") && ($this->_simpleLink != "crop")) {
             $builder
                 ->add('mediadelete', 'checkbox', array(
                     'data'  => false,
                     'required'  => false,
                     'help_block' => $this->_container->get('translator')->trans('pi.media.form.field.mediadelete', array('%s'=>$this->_status)),
                     'label'        => "pi.delete",
                     "label_attr" => array(
                             "class"=> $this->_class,
                     ),
                     "attr"    => array("style"=> $style),
                 ));    
         }     
         
         $builder
         ->add('copyright', 'text', array(
         		"label"     => "Crédit photo",
         		"label_attr" => array(
         				"class"=> $this->_class,
         		),
         		'required'  => false,
         ));         
         
    }

    public function getName()
    {
        return 'sfynx_mediabundle_mediatype_' . $this->_status;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    			'data_class' => 'Sfynx\MediaBundle\Entity\Mediatheque',
    	));
    }    
}
