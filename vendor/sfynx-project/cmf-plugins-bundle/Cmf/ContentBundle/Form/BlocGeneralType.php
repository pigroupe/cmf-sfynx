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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Description of the BlocGeneralType form.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class BlocGeneralType extends AbstractType
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
        $this->_em            = $em;
        $this->_locale        = $container->get('request')->getLocale();
        $this->_container     = $container;
        $this->_insertid     = $insertid;
    }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $format_date = $this->_container->get('pi_app_admin.twig.extension.tool')->getDatePatternByLocalFunction($this->_locale);
        
        $id_media = NULL;
        $id_tags = NULL;
        // get the id of media
        if ( 
        		($builder->getParent()->getData()->getBlocGeneral() instanceof \Cmf\ContentBundle\Entity\BlocGeneral)
        		&& 
        		($builder->getParent()->getData()->getBlocGeneral()->getMedia() instanceof \Sfynx\MediaBundle\Entity\Mediatheque)
        ) {
       		$id_media = $builder->getParent()->getData()->getBlocGeneral()->getMedia()->getId();
        }        
        if (isset($_POST['plugins_contentbundle_articletype']['blocgeneral']['media'])) {
        	$id_media = $_POST['plugins_contentbundle_articletype']['blocgeneral']['media'];
        } elseif (isset($_POST['plugins_contentbundle_pagetype']['blocgeneral']['media'])) {
        	$id_media = $_POST['plugins_contentbundle_pagetype']['blocgeneral']['media'];
        } elseif (isset($_POST['plugins_contentbundle_testtype']['blocgeneral']['media'])) {
        	$id_media = $_POST['plugins_contentbundle_testtype']['blocgeneral']['media'];
        } elseif (isset($_POST['plugins_contentbundle_diaporamatype']['blocgeneral']['media'])) {
        	$id_media = $_POST['plugins_contentbundle_diaporamatype']['blocgeneral']['media'];
        }
        // get the ids of tag
        if (
        		($builder->getParent()->getData()->getBlocGeneral() instanceof \Cmf\ContentBundle\Entity\BlocGeneral)
        		&&
        		($builder->getParent()->getData()->getBlocGeneral()->getTag()->count() >= 1)
        ) {
        	$entities = $builder->getParent()->getData()->getBlocGeneral()->getTag()->toArray();
        	foreach($entities as $k=>$v) {
        		$id_tags[] = $v->getId();
        	}
        }
        if (isset($_POST['plugins_contentbundle_articletype']['blocgeneral']['tag'])) {
        	$id_tags = $_POST['plugins_contentbundle_articletype']['blocgeneral']['tag'];
        } elseif (isset($_POST['plugins_contentbundle_pagetype']['blocgeneral']['tag'])) {
        	$id_tags = $_POST['plugins_contentbundle_pagetype']['blocgeneral']['tag'];
        } elseif (isset($_POST['plugins_contentbundle_testtype']['blocgeneral']['tag'])) {
        	$id_tags = $_POST['plugins_contentbundle_testtype']['blocgeneral']['tag'];
        } elseif (isset($_POST['plugins_contentbundle_diaporamatype']['blocgeneral']['tag'])) {
        	$id_tags = $_POST['plugins_contentbundle_diaporamatype']['blocgeneral']['tag'];
        }    
        
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
             ->add('enabled', 'hidden', array(
                     'data'  => true,
             ))
             ->add('author', 'text', array(
             		'label'    => "Auteur",
             		"label_attr" => array(
             				"class"=>"",
             		),
             		'required'  => true,
             ))    
//              ->add('slug','text',array(
//              		//'read_only' => true,
//              		'label'    => 'URL canonique',
//              ))                      
//              ->add('isvisiblediapo', 'checkbox', array(
//              		'label'	=> 'Visible diaporama',
//              ))
//              ->add('isvisiblecarr', 'checkbox', array(
//              		'label'	=> 'Visible Mosaïque',
//              ))
            ->add('published_at', 'date', array(
                     'widget' => 'single_text', // choice, text, single_text
                     'input' => 'datetime',
                     'format' => $format_date,// 'dd/MM/yyyy', 'MM/dd/yyyy',
                     'required'  => true,
                     "attr" => array(
                             "class"=>"pi_datepicker",
                     ),
                     'label' => 'Date de mise en ligne',
             ))             
             ->add('archive_at', 'date', array(
                     'widget' => 'single_text', // choice, text, single_text
                     'input' => 'datetime',
                     'format' => $format_date,// 'dd/MM/yyyy', 'MM/dd/yyyy',
                     'required'  => false,
                     "attr" => array(
                             "class"=>"pi_datepicker",
                     ),
                     'label' => 'Date d\'expiration',
             ))            
             ->add('created_at', 'date', array(
                     'read_only' => true,
                     'widget' => 'single_text', // choice, text, single_text
                     'input' => 'datetime',
                     'format' => $format_date,//$this->_container->get('pi_app_admin.twig.extension.tool')->getDatePatternByLocalFunction($this->_locale),// 'dd/MM/yyyy', 'MM/dd/yyyy',
                     'required'  => true,
                     'label'    => 'Date de création',
             )) 
             ->add('updated_at',null,array(
             		'attr'=>array('style'=>'display:none;'),
             		"label_attr" => array(
             				"style"=> 'display:none;',
             		),
             ))
            ->add('descriptif', 'textarea', array(
                     'label'    => "Description courte",
                     "label_attr" => array(
                             "class"=>"text_collection",
                     ),     
                     "attr" => array(
                             "class"    =>"pi_editor_easy",
                     ),
                     'required'  => true,
             ))  
             ->add('subrub', 'entity', array(
                     'class' => 'PluginsContentBundle:Rub',
                     'query_builder' => function(EntityRepository $er) {
                         return $er->createQueryBuilder('k')
                         ->select('k')
                         ->andWhere('k.parent IS NOT NULL')
                         ->orderBy('k.id', 'ASC');
                     },
                    'empty_value' => 'Choisir une sous-rubrique',
                    'multiple'	=> false,
                    'group_by'	=> "parent.title",
                    'required'  => true,
                    'label'	=> "Rubrique principale",
                    'constraints' => array(
                        //new Constraints\NotBlank(),
                    ),
                    "label_attr" => array(
                    		"class"=>"classement_collection",
                    ),
                    "attr" => array(
                    		"class"=>"pi_simpleselect",
                    ),
             ))          
             ->add('rub', 'entity', array(
                     'class' => 'PluginsContentBundle:Rub',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('k')
                            ->select('k')
                            ->andWhere('k.parent IS NOT NULL')
                            ->orderBy('k.id', 'ASC');
                    },
                    'empty_value' => 'Choisir une sous-rubrique',
                    'multiple'	=> true,
                    'group_by'	=> "parent.title",
                    'required'  => false,
                    'label'	=> "Rubriques associées",
	                "label_attr" => array(
	                    "class"=>"classement_collection",
	                ),
	                "attr" => array(
	                    "class"=>"pi_multiselect",
	                ),
            ))
             ->add('tag', 'entity', array(
             		'class' => 'PluginsContentBundle:Tag',
             		'query_builder' => function(EntityRepository $er) use ($id_tags) {
             			$translatableListener = $this->_container->get('gedmo.listener.translatable');
             			$translatableListener->setTranslationFallback(true);
             			return $er->createQueryBuilder('a')
             			->select('a')
             			->where("a.id IN (:id)")
                        ->andWhere('a.enabled = 1')
             			->setParameter('id', $id_tags)
             			//->where("a.status = 'image'")
             			//->andWhere("a.image IS NOT NULL")
             			//->andWhere("a.enabled = 1")
             			->orderBy('a.id', 'ASC')
             			;
             		},
             		//'property' => 'title',
             		'empty_value' => 'pi.form.label.select.choose.tag',
             		'label' => "Tag",
             		'multiple' => true,
             		'required'  => false,
             		'constraints' => array(
             				new Constraints\NotBlank(),
             		),
             		"label_attr" => array(
             				"class"=>"classement_collection",
             		),
             		"attr" => array(
             				"class"=>"pi_multiselect ajaxselect", // ajaxselect
             				"data-url"=>$this->_container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_content_tag_selectentity_ajax"),
             				//"data-selectid" => json_encode($id_tags)
                            "data-max" => 19,
             		),
             		'widget_suffix' => '<a class="button-ui-mediatheque button-ui-dialog"
             				title="Ajouter un tag à la sélection"
             				data-title="Tags"
             				data-href="'.$this->_container->get('bootstrap.RouteTranslator.factory')->getRoute("admin_content_tag_new", array("NoLayout"=>"false", "category"=>'')).'"
             				data-selectid="#piapp_gedmobundle_tagtype_id"
             				data-selecttitle="#piapp_gedmobundle_tagtype_title"
             				data-insertid="#'.str_replace('_blocgeneral_media', '_blocgeneral_tag', $this->_insertid).'"
             				data-inserttype="multiselect"
             				></a>',
             ))
             ->add('metaKeywords', 'text', array(
             		'label'    => "metaKeywords",
             		"label_attr" => array(
             				"class"=>"meta_definition",
             		),
             		'required'  => false,
             ))
             ->add('metaDescription', 'textarea', array(
             		"label" => "pi.form.label.field.meta_description",
             		"label_attr" => array(
             				"class"=>"meta_definition",
             		),
             		'required'  => false,
             ))             
            //->add('media', new \OrApp\OrGedmoBundle\Form\MediaType($this->_container, $this->_em, 'image', 'bg_image_collection', "simpleLink", 'pi.form.label.media.picture'))
            ->add('media', 'entity', array(
             		'class' => 'PiAppGedmoBundle:Media',
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
            		"label_attr" => array(
            				"class"=> 'bg_image_collection',
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
        return 'plugins_contentbundle_blocgeneraltype';
    }
    
    /**
   * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
   */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cmf\ContentBundle\Entity\BlocGeneral',
        ));
    }    
        
}
