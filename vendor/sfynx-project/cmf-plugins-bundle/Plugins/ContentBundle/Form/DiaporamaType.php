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
 * Description of the DiaporamaType form.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class DiaporamaType extends AbstractType
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
        
        $array_media = null;
        if ($builder->getData() instanceof \Plugins\ContentBundle\Entity\Diaporama && $builder->getData()->getMedias() instanceof \Doctrine\ORM\PersistentCollection){
            $array_media = array();
            foreach($builder->getData()->getMedias() as $media_diaporama){
                if ($media_diaporama instanceof \Plugins\ContentBundle\Entity\MediasDiaporama && $media_diaporama->getMedia() instanceof \PiApp\GedmoBundle\Entity\Media){
                    array_push($array_media, $media_diaporama->getMedia()->getId());
                }
            }
        }
        //$id_media = $builder->getParent()->getData()->getBlocGeneral()->getMedia()->getId();
        //print_r(get_class($builder->getData()->getMedias()));exit;
        if (isset( $_POST['plugins_contentbundle_diaporamatype']['medias'])) {
            $array_media =array();
            foreach($_POST['plugins_contentbundle_diaporamatype']['medias'] as $media){
                array_push($array_media, $media['media']);
            }
        }   
        //print_r($_POST);exit;
        $_POST['_diaporama_medias_'] = $array_media;
        
        $builder             
             ->add('enabled', 'hidden')
             ->add('blocgeneral', new \Plugins\ContentBundle\Form\BlocGeneralType($this->_em, $this->_container, "plugins_contentbundle_diaporamatype_blocgeneral_media")) 
             ->add('medias', 'collection', array(
                 'type' => new \Plugins\ContentBundle\Form\MediasDiaporamaType($this->_em, $this->_container),
                 'allow_add' => true,
                 'allow_delete' => true,
                 'by_reference' => false,
            ))
        ;
    }

    public function getName()
    {
        return 'plugins_contentbundle_diaporamatype';
    }
        
}
