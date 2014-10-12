<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Form
 * @package    CMS_Form
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-07
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManager;

/**
 * Description of the KeyWordType form.
 *
 * @subpackage   Admin_Form
 * @package    CMS_Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class KeyWordType extends AbstractType
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;
    
    
    /**
     * Constructor.
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @return void
     */
    public function __construct(EntityManager $em)
    {
        $this->_em = $em;
    }    
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choiceList = $this->_em->getRepository("SfynxCmfBundle:KeyWord")->getArrayAllGroupName();
        if (!isset($choiceList) || !count($choiceList))
            $choiceList = array();
                
        $builder
            ->add('enabled', 'checkbox', array(
                    'data'  => true,
                     'label'    => 'pi.form.label.field.enabled',
            ))
            ->add('groupname', 'choice', array(
                    'choices'   => $choiceList,
                    'multiple'    => false,
                    'required'  => false,
                    'empty_value' => 'pi.form.label.select.choose.option',
                    "attr" => array(
                            "class"=>"pi_simpleselect",
                    ),
            ))
            ->add('groupnameother', 'text', array(
                    "label"     => "pi.form.label.field.or",
                    'required'  => false,
            ))            
            ->add('name', 'text', array(
                 'label' => "pi.form.label.field.name"
             ))
        ;
    }

    public function getName()
    {
        return 'piapp_adminbundle_keywordtype';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
                'data_class' => 'Sfynx\CmfBundle\Entity\KeyWord', // Ni de modifier la classe ici.
        );
    }    
}
