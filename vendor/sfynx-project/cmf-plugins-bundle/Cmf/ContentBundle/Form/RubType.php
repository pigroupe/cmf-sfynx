<?php
/**
 * This file is part of the <PI_CRUD> project.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 20XX-XX-XX
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cmf\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Validator\Constraints;

/**
 * Description of the RubType form.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class RubType extends AbstractType
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
     * @param string    $locale
     * @return void
     */
    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->_em             = $em;
        $this->_locale        = $container->get('request')->getLocale();
        $this->_container     = $container;
    }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id_media = NULL;

        if (($builder->getData()->getMedia() instanceof \PiApp\GedmoBundle\Entity\Media)) {
            $id_media = $builder->getData()->getMedia()->getId();
        }

        if (isset( $_POST['plugins_contentbundle_rubtype']['media'])) {
            $id_media = $_POST['plugins_contentbundle_rubtype']['media'];
        }
        
        $builder    
             ->add('enabled', 'checkbox', array(
                     'label'    => 'pi.form.label.field.enabled',
            ))       
             ->add('parent', 'entity', array(
                    'class' => 'PluginsContentBundle:Rub',
                    'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('k')
                        ->select('k')
                        ->orderBy('k.lft', 'ASC');
                    },
                    'empty_value' => 'pi.form.label.select.choose.option',
                    'multiple'    => false,
                    'required'  => false,
            ))            
             ->add('title', 'text', array(
                     'label'    => "pi.form.label.field.title",
                     "label_attr" => array(
                             "class"=>"text_collection",
                     ),
                     'required'  => false,
             ))
            ->add('section', 'text', array(
                    'label'    => "Section",
                    "label_attr" => array(
                            "class"=>"text_collection",
                    ),
                    'required'  => false,
            ))            
            ->add('descriptif', 'textarea', array(
                    'label'    => "Texte d'accroche",
                    "label_attr" => array(
                            "class"=>"text_collection",
                    ),     
                    "attr" => array(
                            "class"    =>"pi_editor_easy",
                    ),
                    'required'  => false,
            ))
            ->add('titleref', 'text', array(
                'label'    => "Titre de référencement",
                "attr" => array(
                    "class"    =>"pi_editor_easy",
                ),
                'required'  => false,
            ))
            ->add('referencement', 'textarea', array(
                    'label'    => "Texte de referencement",
                    "label_attr" => array(
                            "class"=>"text_collection",
                    ),     
                    "attr" => array(
                            "class"    =>"pi_editor_easy",
                    ),
                    'required'  => false,
            ))
             ->add('url', 'text', array(
                     'label'=>'pi.form.label.field.url',
                     'required'  => false,
             ))                          
            ->add('media', 'entity', array(
                'class' => 'PiAppGedmoBundle:Media',
                'query_builder' => function(EntityRepository $er) use ($id_media) {
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
             		//'property' => 'title',
             		'empty_value' => 'pi.form.label.select.choose.media',
             		'multiple' => false,
             		'required'  => true,
//             		'constraints' => array(
//             				new Constraints\NotBlank(),
//             		),
             		"label_attr" => array(
             				"class"=> 'bg_image_collection',
             		),
                "attr" => array(
                    "class"=>"pi_simpleselect ajaxselect", // ajaxselect
                    "data-url"=>$this->_container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_gedmo_media_selectentity_ajax", array('type'=>'image')),
                    //"data-selectid" => $id_media
                ),
             		'label' => "Media",
             		'widget_suffix' => '<a class="button-ui-mediatheque button-ui-dialog"
             				title="Ajouter une image à la médiatheque"
             				data-title="Mediatheque"
             				data-href="'.$this->_container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_gedmo_media_new", array("NoLayout"=>"false", "category"=>'', 'status'=>'image')).'"
             				data-selectid="#piapp_gedmobundle_mediatype_id"
             				data-selecttitle="#piapp_gedmobundle_mediatype_title"
             				data-insertid="#plugins_contentbundle_rubtype_media"
             				data-inserttype="multiselect"
             				></a>',
             ))                            
                ;
            //}              

    }

    public function getName()
    {
        return 'plugins_contentbundle_rubtype';
    }
        
}
