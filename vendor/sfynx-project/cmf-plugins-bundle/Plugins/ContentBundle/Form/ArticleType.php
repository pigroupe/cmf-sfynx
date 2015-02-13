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
namespace Plugins\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Description of the ArticleType form.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ArticleType extends AbstractType
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
    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->_em             = $em;
        $this->_locale        = $container->get('request')->getLocale();
        $this->_container     = $container;
    }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder             
             ->add('enabled', 'hidden')
             ->add('type', 'choice', array(
             		'choices' => array(
           				'article' => 'Article',
           				'image' => 'Image',
	           			'grande_image' => 'Grande image',
    	       			'large_image' => 'Image Large',
        	   			'video' => 'Vidéo'
             		)
             ))             
             ->add('popin', 'checkbox', array(
             		'label'	=> 'Visite du mois',
             ))
             ->add('blocgeneral', new \Plugins\ContentBundle\Form\BlocGeneralType($this->_em, $this->_container, 'plugins_contentbundle_articletype_blocgeneral_media'))
             ->add('url', 'text', array(
             		"label_attr" => array(
             				"class"=> 'bg_image_collection',
             		)
             ))
             ->add('alias', 'text', array(
             		'label'	=> 'Alias de l\'url',
             		'required' => false,
             		"label_attr" => array(
             				"class"=> 'bg_image_collection',
             		)
             ))
             ->add('content', 'textarea', array(
             		'label'    => "pi.form.label.field.content",
             		"label_attr" => array(
             				"class"=>"text_collection",
             		),
             		"attr" => array(
             				"class"    =>"pi_editor_easy",
             		),
             		'required'  => false,
             ))             
        ;
    }

    public function getName()
    {
        return 'plugins_contentbundle_articletype';
    }
        
}
