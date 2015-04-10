<?php
/**
 * This file is part of the <PI_CRUD> project.
 *
 * @category PI_CRUD_Form
 * @package  Form
 * @author   Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since    20XX-XX-XX
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace OrApp\OrGedmoBundle\Form;

use \Sfynx\MediaBundle\Form\MediathequeType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

/**
 * Description of the MediaType form.
 *
 * @category PI_CRUD_Form
 * @package  Form
 * @author   Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class MediaType extends MediathequeType
{
    /**
     * Constructor.
     *
     * @param EntityManager $em     The em service
     * @param string        $status ['file', 'image', 'youtube', 'dailymotion']
     * 
     * @return void
     */
    public function __construct(ContainerInterface $container, EntityManager $em, $status = "image", $class =  "media_collection", $simpleLink = "all", $labelLink = "", $context = "")
    {
        parent::__construct($container, $em, $status, $class, $simpleLink, $labelLink, $context);
    }
}
