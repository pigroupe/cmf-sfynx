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
namespace PiApp\GedmoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of the CategoryType form.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class CategoryType extends AbstractType
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
     * Constructor.
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @return void
     */
    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->_em = $em;
        $this->_container     = $container;
    }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id_media = null;
        // get the id of media
        if ($builder->getData()->getMedia()
                instanceof \Sfynx\MediaBundle\Entity\Mediatheque
        ) {
            $id_media = $builder->getData()->getMedia()->getId();
        }
        if (isset($_POST['piapp_gedmobundle_categorytype']['media'])) {
            $id_media = $_POST['piapp_gedmobundle_categorytype']['media'];
        } 
        
        $builder 
            ->add('type', 'choice', array(
                    'choices'   => array(
                                        0=>"pi.category.type.0", 
                                        1=>"pi.category.type.1",
                                        2=>"pi.category.type.2",
                                        3=>"pi.category.type.3", 
                                        4=>"pi.category.type.4", 
                                        5=>"pi.category.type.5", 
                                    ),
                    'label'    => 'pi.page.form.status',
                    'required'  => true,
                    'multiple'    => false,
                    'expanded' => true,
            ))
             ->add('name', 'text', array(
                 'label' => "pi.form.label.field.name"
             ))
             ->add('subtitle', 'text', array(
                     'label'    => "pi.form.label.field.subtitle",
                     'required'  => false,
             ))
             ->add('descriptif', 'textarea', array(
                     'label'    => "pi.form.label.field.description",
                     "label_attr" => array(
                             "class"=>"text_collection",
                     ),
                     "attr" => array(
                             "class"    =>"pi_editor_simple_easy",
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
        return 'piapp_gedmobundle_categorytype';
    }
        
}
