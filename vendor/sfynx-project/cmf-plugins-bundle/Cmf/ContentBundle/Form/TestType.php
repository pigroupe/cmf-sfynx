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

/**
 * Description of the TestType form.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class TestType extends AbstractType
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
             ->add('blocgeneral', new \Cmf\ContentBundle\Form\BlocGeneralType($this->_em, $this->_container, "plugins_contentbundle_testtype_blocgeneral_media"))
             ->add('questions', 'collection', array(
                 'type' => new \Cmf\ContentBundle\Form\TestQuestionType($this->_em, $this->_container),
                 'allow_add' => true,
                 'allow_delete' => true,
                 'by_reference' => false,
            ))
             ->add('titreprofil1', 'text', array(
                     'label'    => "Titre profil 1",
                     "label_attr" => array(
                             "class"=>"profil_collection",
                     ),
                     'required'  => true,
             ))
             ->add('profil1', 'textarea', array(
                     'label'    => "Description profil 1",
                     "attr" => array(
                             "class"    =>"pi_editor_easy",
                     ),               
                     "label_attr" => array(
                             "class"=>"profil_collection",
                     ),
                     'required'  => false,
             ))          
             ->add('titreprofil2', 'text', array(
                     'label'    => "Titre profil 2",
                     "label_attr" => array(
                             "class"=>"profil_collection",
                     ),
                     'required'  => true,
             ))
             ->add('profil2', 'textarea', array(
                     'label'    => "Description profil 2",
                     "attr" => array(
                             "class"    =>"pi_editor_easy",
                     ),               
                     "label_attr" => array(
                             "class"=>"profil_collection",
                     ),
                     'required'  => false,
             ))          
             ->add('titreprofil3', 'text', array(
                     'label'    => "Titre profil 3",
                     "label_attr" => array(
                             "class"=>"profil_collection",
                     ),
                     'required'  => true,
             )) 
             ->add('profil3', 'textarea', array(
                     'label'    => "Description profil 3",
                     "attr" => array(
                             "class"    =>"pi_editor_easy",
                     ),               
                     "label_attr" => array(
                             "class"=>"profil_collection",
                     ),
                     'required'  => false,
             ))          
        ;
    }

    public function getName()
    {
        return 'plugins_contentbundle_testtype';
    }
        
}
