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
namespace OrApp\OrGedmoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

use \PiApp\GedmoBundle\Form\MediaType as PiMediaType;

/**
 * Description of the MediaType form.
 *
 * @category   PI_CRUD_Form
 * @package    Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class MediaType extends PiMediaType
{
    /**
     * Constructor.
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param string $status    ['file', 'image', 'youtube', 'dailymotion']
     * @return void
     */
    public function __construct(ContainerInterface $container, EntityManager $em, $status = "image", $class =  "media_collection", $simpleLink = "all", $labelLink = "", $context = "")
    {
        parent::__construct($container, $em, $status, $class, $simpleLink, $labelLink, $context);
    }
}