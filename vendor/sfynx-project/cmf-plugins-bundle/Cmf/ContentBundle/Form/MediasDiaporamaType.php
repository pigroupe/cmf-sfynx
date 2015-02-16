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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Validator\Constraints;

/**
 * Description of the MediasDiaporamaType form.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class MediasDiaporamaType extends AbstractType
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
    public function __construct(EntityManager $em, ContainerInterface $container, $insertid = "")
    {
        $this->_em             = $em;
        $this->_locale        = $container->get('request')->getLocale();
        $this->_container     = $container;
        $this->_insertid     = $insertid;
    }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if (isset( $_POST['_diaporama_medias_']) &&  $_POST['_diaporama_medias_']) {
            $array_media =$_POST['_diaporama_medias_'];
        } else {
            $array_media = null;
        }

        //print_r($builder->getData());
        
        $builder   
	         ->add('title', 'text', array(
	        		'label'    => "pi.form.label.field.title",
	        		"label_attr" => array(
	        				"class"=>"",
	        		),
	        		'required'  => true,
	        		'constraints' => array(
	        				new Constraints\NotBlank(),
	        		),
	         ))    
            ->add('descriptif')
            ->add('media', 'entity', array(
             		'class' => 'PiAppGedmoBundle:Media',
            		'query_builder' => function(EntityRepository $er) use ($array_media) {
            			$translatableListener = $this->_container->get('gedmo.listener.translatable');
            			$translatableListener->setTranslationFallback(true);         			
            			return $er->createQueryBuilder('a')
            			->select('a')
            			->where("a.id IN (:id)")
            			->setParameter('id', $array_media)
            			;
            		},
            		'empty_value' => 'pi.form.label.select.choose.media',
            		'label' => "Media",
            		'multiple' => false,
					'required'  => true,
             		'constraints' => array(
             				new Constraints\NotBlank(),
             		),
            		"attr" => array(
            				"class"=>"pi_simpleselect ajaxselect", // ajaxselect
            				"data-url"=>$this->_container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_gedmo_media_selectentity_ajax", array('type'=>'image')),
            				//"data-selectid" => $id_media
            				"data-max" => 50,
            		),
            		'widget_suffix' => '<a class="button-ui-mediatheque button-ui-dialog"
             				title="Ajouter une image à la médiatheque"
             				data-title="Mediatheque"
             				data-href="'.$this->_container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_gedmo_media_new", array("NoLayout"=>"false", "category"=>'', 'status'=>'image')).'"
             				data-selectid="#piapp_gedmobundle_mediatype_id"
             				data-selecttitle="#piapp_gedmobundle_mediatype_title"
             				data-insertid="#'.$this->_insertid.'"
             				data-inserttype="multiselect"
             				></a>',            		
            ))          
        ;
    }

    public function getName()
    {
        return 'plugins_contentbundle_mediasdiaporamatype';
    }
    

    /**
   * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
   */    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cmf\ContentBundle\Entity\MediasDiaporama',
        ));
    }  
        
}
