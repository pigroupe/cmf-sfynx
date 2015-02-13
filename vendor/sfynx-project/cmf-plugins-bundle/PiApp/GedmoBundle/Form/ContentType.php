<?php
/**
 * This file is part of the <PI_CRUD> project.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-07-02
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

/**
 * Description of the ContentType form.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ContentType extends AbstractType
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
        $this->_em             = $em;
        $this->_container     = $container;
        $this->_locale        = $locale;        
    }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id_category = null;
        if ($builder->getData()->getCategory()
                instanceof Category
        ) {
            $id_category = $builder->getData()->getCategory()->getId();
        }
        if (isset($_POST['piapp_gedmobundle_contenttype']['category'])) {
            $id_category = $_POST['piapp_gedmobundle_contenttype']['category'];
        }          
        $builder   
            ->add('enabled', 'checkbox', array(
                    'data'  => true,
                    'label'    => 'pi.form.label.field.enabled',
                    "label_attr" => array(
                            "class"=>"content_collection",
                    ),
            ))
//             ->add('published_at', 'date', array(
//                     'widget' => 'single_text', // choice, text, single_text
//                     'input' => 'datetime',
//                     'format' => $this->_container->get('sfynx.tool.twig.extension.tool')->getDatePatternByLocalFunction($this->_locale),// 'dd/MM/yyyy', 'MM/dd/yyyy',
//                     'empty_value' => array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'),
//                     //'pattern' => "{{ day }}/{{ month }}/{{ year }}",
//                     //'data_timezone' => "Europe/Paris",
//                     //'user_timezone' => "Europe/Paris",
//                     "attr" => array(
//                             "class"=>"pi_datepicker",
//                     ),
//                     'label'    => 'pi.form.label.date.publication',
//                     "label_attr" => array(
//                             "class"=>"content_collection",
//                     ),
//             ))
//             ->add('archive_at', 'date', array(
//                     'widget' => 'single_text', // choice, text, single_text
//                     'input' => 'datetime',
//                     'format' => 'MM/dd/yyyy',
//                     'empty_value' => array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'),
//                     //'pattern' => "{{ day }}/{{ month }}/{{ year }}",
//                     //'data_timezone' => "Europe/Paris",
//                     //'user_timezone' => "Europe/Paris",
//                     "attr" => array(
//                             "class"=>"pi_datepicker",
//                     ),
//                     'label'    => 'pi.form.label.date.archivage',
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
                        ->setParameter('type', 3);
                    },
                    'property' => 'name',
                    'empty_value' => 'pi.form.label.select.choose.category',
                    'label'    => "pi.form.label.field.category",
                    'multiple'    => false,
                    'required'  => false,
                    "attr" => array(
                        "class"=>"pi_simpleselect ajaxselect", // ajaxselect
                        "data-url"=>$this->_container->get('sfynx.tool.route.factory')->getRoute("admin_gedmo_category_selectentity_ajax", array('type'=> 3)),
                        "data-selectid" => $id_category,
                        "data-max" => 50,
                    ),
                    'widget_suffix' => '<a class="button-ui-mediatheque button-ui-dialog"
                                    title="Ajouter une catégorie"
                                    data-title="Catégorie"
                                    data-href="'.$this->_container->get('sfynx.tool.route.factory')->getRoute("admin_gedmo_category_new", array("NoLayout"=>"false", 'type'=> 3)).'"
                                    data-selectid="#piapp_gedmobundle_categorytype_id"
                                    data-selecttitle="#piapp_gedmobundle_categorytype_name"
                                    data-insertid="#piapp_gedmobundle_contenttype_category"
                                    data-inserttype="multiselect"
                                    ></a>', 
            ))   
            ->add('descriptif', 'text', array(
                     'label'    => 'pi.form.label.field.description',
                    "label_attr" => array(
                            "class"=>"content_collection",
                    ),
             ))  
            ->add('pageurl', 'entity', array(
                     'class' => 'SfynxCmfBundle:Page',
                     'query_builder' => function(EntityRepository $er) {
                         return $er->getAllPageHtml();
                     },
                     'property' => 'route_name',
                     'empty_value' => 'pi.form.label.select.choose.option',
                     'multiple'    => false,
                     'required'  => false,
                     "label"     => "pi.form.label.field.url",
                     "attr" => array(
                             "class"=>"pi_simpleselect",
                     ),
                     "label_attr" => array(
                             "class"=>"content_collection",
                     ),
             ))                        
             ->add('url', 'text', array(
                     'required'  => false,
                     "label"     => "pi.form.label.field.or",
                     "label_attr" => array(
                             "class"=>"content_collection",
                     ),                     
             ))         
             ->add('content', 'textarea', array(
                    "attr" => array(
                            "class"    =>"pi_editor_simple_easy",
                    ),
                     'required'  => false,
                     'label'    => "pi.form.label.field.content",
                     "label_attr" => array(
                             "class"=>"content_collection",
                     ),
            ))  
          ->add('pagecssclass')
        ;
    }

    public function getName()
    {
        return 'piapp_gedmobundle_contenttype';
    }
        
}
