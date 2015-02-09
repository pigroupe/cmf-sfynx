<?php
/**
 * This file is part of the <PI_CRUD> project.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-03-22
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
 * Description of the MenuType form.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class MenuType extends AbstractType
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
        if (isset($_POST['piapp_gedmobundle_menutype']['media'])) {
            $id_media = $_POST['piapp_gedmobundle_menutype']['media'];
        }
        
        $builder            
             ->add('enabled', 'checkbox', array(
                    //'data'  => true,
                    'label'    => 'pi.form.label.field.enabled',
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
                        ->setParameter('type', 5);
                    },
                    'property' => 'name',
                    'empty_value' => 'pi.form.label.select.choose.category',
                    'label'    => "pi.form.label.field.category",
                    'multiple'    => false,
                    'required'  => false,
                    "attr" => array(
                            "class"=>"pi_simpleselect",
                    ),
            ))                 
             ->add('parent', 'entity', array(
                    'class' => 'PiAppGedmoBundle:Menu',
                    'query_builder' => function(EntityRepository $er) {
                        $translatableListener = $this->_container->get('gedmo.listener.translatable');
                        $translatableListener->setTranslationFallback(true);
                        return $er->createQueryBuilder('k')
                        ->select('k')
                        ->orderBy('k.lft', 'ASC');
                    },
                    'empty_value' => 'pi.form.label.select.choose.option',
                    'multiple'    => false,
                    'required'  => false,
                    "attr" => array(
                            "class"=>"pi_simpleselect",
                    ),
            ))
            ->add('title', 'text', array(
                     'label'    => "pi.form.label.field.title",
             )) 
             ->add('subtitle', 'text', array(
                     'label'    => "pi.form.label.field.subtitle",
                     'required'  => false,
             ))
             ->add('configCssClass')
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
             ))
             ->add('url', 'text', array(
                     'label'=>'pi.form.label.field.or',
                     'required'  => false,
             ))     
             ->add('blank', 'checkbox', array(
                     //'data'  => false,
                     'label'=>'pi.form.label.field.blank',
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
        return 'piapp_gedmobundle_menutype';
    }
        
}
