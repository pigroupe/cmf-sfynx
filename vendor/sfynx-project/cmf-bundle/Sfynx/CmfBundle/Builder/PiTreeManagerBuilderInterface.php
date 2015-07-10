<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   PiApp
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Builder;

/**
 * PiTreeManagerBuilderInterface interface.
 *
 * @subpackage   PiApp
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface PiTreeManagerBuilderInterface
{
    /**
     * Call the tree render source method.
     *
     * @param string $id
     * @param string $lang
     * @param string $params
     * @return string
     * @access    public
     *
     * @author Etienne de Longeaux <etienne_delongeaux@hotmail.com>
     * @since 2012-04-19
     */
    public function renderSource($id, $lang = '', $params = null);
    
    /**
     * Return the build tree result of a gedmo tree entity, with class options.
     *
     * @param string    $locale
     * @param string    $entity
     * @param string    $category
     * @param string    $separatorClass
     * @param string    $ulClass
     * @param string    $liClass
     * @param array        $options
     * @access    public
     * @return string
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function defaultNavigation($locale, $entity, $category, $template, $separatorClass = "", $ulClass = "", $liClass = "", $params = null);
    
    /**
     * Return the build tree result of a gedmo tree entity, without class options.
     * 
     * @param string    $locale
     * @param string    $entity
     * @param string    $category
     * @param array        $params
     * @return string
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2012-04-19
     */
    public function defaultOrganigram($locale, $entity, $category, $params = null);    
}