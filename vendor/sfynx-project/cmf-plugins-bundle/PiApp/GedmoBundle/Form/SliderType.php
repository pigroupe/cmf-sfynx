<?php
/**
 * This file is part of the <PI_CRUD> project.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-04-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\GedmoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use PiApp\GedmoBundle\Entity\Category;
use Sfynx\MediaBundle\Entity\Mediatheque;

/**
 * Description of the SliderType form.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SliderType extends AbstractType
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
    protected $_locale;    
    
    /**
     * Constructor.
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @return void
     */
    public function __construct(EntityManager $em, $locale, ContainerInterface $container)
    {
        $this->_em        = $em;
        $this->_container = $container;
        $this->_locale    = $locale;        
    }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id_media = null;
        $id_category = null;
        if ($builder->getData()->getMedia()
                instanceof Mediatheque
        ) {
            $id_media = $builder->getData()->getMedia()->getId();
        }
        if (isset($_POST['piapp_gedmobundle_slidertype']['media'])) {
            $id_media = $_POST['piapp_gedmobundle_slidertype']['media'];
        }       
        //
        // get the id of media
        if ($builder->getData()->getCategory()
                instanceof Category
        ) {
            $id_category = $builder->getData()->getCategory()->getId();
        }
        if (isset($_POST['piapp_gedmobundle_slidertype']['category'])) {
            $id_category = $_POST['piapp_gedmobundle_slidertype']['category'];
        }   
        //
        $builder  
            ->add('enabled', 'checkbox', array(
                    'data'  => true,
                    'label'    => 'pi.form.label.field.enabled',
            ))
//             ->add('published_at', 'date', array(
//                     'widget'     => 'single_text', // choice, text, single_text
//                     'input'     => 'datetime',
//                     'format'     => $this->_container->get('sfynx.tool.twig.extension.tool')->getDatePatternByLocalFunction($this->_locale),// 'dd/MM/yyyy', 'MM/dd/yyyy',
//                     "attr"     => array(
//                             "class"=>"pi_datepicker",
//                     ),
//                     'label'    => 'pi.form.label.date.publication',
//             ))
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
            ->add('title', 'text', array(
                    'label'        => "pi.form.label.field.title",
                    'required'  => false,
            ))
            ->add('subtitle', 'text', array(
                    "label" => 'Sub title',
                    "label_attr" => array(
                            "class"=>"detail_collection",
                    ),
                    'required'  => false,
            ))   
            ->add('descriptifleft', 'textarea', array(
                     'required'  => false,
                    "label" => 'Description Left resume',
                     "label_attr" => array(
                             "class"=>"detail_collection",
                     ),
                    "attr" => array(
                            "class"    =>"pi_editor_simple_easy",
                    ),
            ))
            ->add('descriptifright', 'textarea', array(
                     'required'  => false,
                    "label" => 'Description Right resume',
                     "label_attr" => array(
                             "class"=>"detail_collection",
                     ),
                    "attr" => array(
                            "class"    =>"pi_editor_simple_easy",
                    ),
            ))
            ->add('page', 'entity', array(
                    'class' => 'SfynxCmfBundle:Page',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->getAllPageHtml();
                    },
                    'property' => 'route_name',
                    'empty_value' => 'pi.form.label.select.choose.option',
                    "label"     => "pi.form.label.field.url",
                    'multiple'    => false,
                    'required'  => false,
                    "attr" => array(
                            "class"=>"pi_simpleselect",
                    ),
                    "label_attr" => array(
                            "class"=>"link_collection",
                    ),
            )) 
            ->add('pagetitle', 'text', array(
                    "label" => 'Page title',
                    "label_attr" => array(
                            "class"=>"link_collection",
                    ),
                    'required'  => false,
            ))            
            ->add('meta_keywords', 'textarea', array(
                    "label" => "pi.form.label.field.meta_keywords",
                    "label_attr" => array(
                            "class"=>"meta_definition",
                    ),
                    'required'  => false,
            ))
            ->add('meta_description', 'textarea', array(
                    "label" => "pi.form.label.field.meta_description",
                    "label_attr" => array(
                            "class"=>"meta_definition",
                    ),
                    'required'  => false,
            ))            
            //->add('media', new \Sfynx\MediaBundle\Form\MediathequeType($this->_container, $this->_em, 'image', 'image_collection', "simpleLink", 'pi.form.label.media.picture'))
            ->add('media', 'entity', array(
             		'class' => 'SfynxMediaBundle:Mediatheque',
            		'query_builder' => function(EntityRepository $er) use ($id_media) {
                            $translatableListener = $this->_container->get('gedmo.listener.translatable');
                            $translatableListener->setTranslationFallback(true);            			
                            return $er->createQueryBuilder('a')
                            ->select('a')
                            ->where("a.id IN (:id)")
                            ->setParameter('id', $id_media)
                            //->where("a.status = 'image'")
                            //->andWhere("a.image IS NOT NULL")
                            //->andWhere("a.enabled = 1")
                            //->orderBy('a.id', 'ASC')
                            ;
            		},
            		//'property' => 'id',
            		'empty_value' => 'pi.form.label.select.choose.media',
            		'label' => "Media",
            		'multiple' => false,
                            'required'  => false,
             		'constraints' => array(
                            //new Constraints\NotBlank(),
             		),
            		"label_attr" => array(
                            "class"=> 'bg_image_collection',
            		),
            		"attr" => array(
                            "class"=>"pi_simpleselect ajaxselect", // ajaxselect
                            "data-url"=>$this->_container->get('sfynx.tool.route.factory')->getRoute("admin_gedmo_media_selectentity_ajax", array('type'=>'image')),
                            "data-selectid" => $id_media,
                            "data-max" => 50,
            		),
            		'widget_suffix' => '<a class="button-ui-mediatheque button-ui-dialog"
             				title="Ajouter une image à la médiatheque"
             				data-title="Mediatheque"
             				data-href="'.$this->_container->get('sfynx.tool.route.factory')->getRoute("admin_gedmo_media_new", array("NoLayout"=>"false", "category"=>'', 'status'=>'image')).'"
             				data-selectid="#sfynx_mediabundle_mediatype_id"
             				data-selecttitle="#sfynx_mediabundle_mediatype_title"
             				data-insertid="#piapp_gedmobundle_blocktype_media"
             				data-inserttype="multiselect"
             				></a>',            		
             ))                               
        ;
    }

    public function getName()
    {
        return 'piapp_gedmobundle_slidertype';
    }
        
}
