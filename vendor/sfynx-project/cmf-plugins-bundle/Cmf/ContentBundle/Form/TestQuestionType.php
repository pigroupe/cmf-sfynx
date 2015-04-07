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

/**
 * Description of the TestQuestionType form.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class TestQuestionType extends AbstractType
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
             ->add('title', 'text', array(
                     'label'    => "pi.form.label.field.title",
                     "label_attr" => array(
                             "class"=>"test_collection",
                     ),
                     'required'  => false,
             ))
             ->add('reponse1', 'text', array(
                     'label'    => "Reponse 1",
                     "label_attr" => array(
                             "class"=>"test_collection",
                     ),
                     'required'  => false,
             ))
             ->add('profil1', 'choice', array(
                    'required'  => true,      
                    'choices'   => array(
                               '1' => ' 1 ',
                               '2' => ' 2 ',
                               '3' => ' 3 '
                       ),
               		'preferred_choices' => array('1'),
             		"label_attr" => array(
             				"class"=>"test_collection",
             		),
             ))                 
             ->add('reponse2', 'text', array(
                     'label'    => "Reponse 2",
                     "label_attr" => array(
                             "class"=>"test_collection",
                     ),
                     'required'  => false,
             ))
             ->add('profil2', 'choice', array(
                    'required'  => true,      
                    'choices'   => array(
                               '1' => ' 1 ',
                               '2' => ' 2 ',
                               '3' => ' 3 '
                       ),
               		'preferred_choices' => array('2'),
             		"label_attr" => array(
             				"class"=>"test_collection",
             		),
             ))                  
             ->add('reponse3', 'text', array(
                     'label'    => "Reponse 3",
                     "label_attr" => array(
                             "class"=>"test_collection",
                     ),
                     'required'  => false,
             ))
             ->add('profil3', 'choice', array(
                    'required'  => true,      
                    'choices'   => array(
                               '1' => ' 1 ',
                               '2' => ' 2 ',
                               '3' => ' 3 '
                       ),
               		'preferred_choices' => array('3'),
             		"label_attr" => array(
             				"class"=>"test_collection",
             		),
             ))                  
        ;
    }

    public function getName()
    {
        return 'plugins_contentbundle_test_questiontype';
    }

    /**
   * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
   */    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cmf\ContentBundle\Entity\TestQuestion',
        ));
    }      
}
