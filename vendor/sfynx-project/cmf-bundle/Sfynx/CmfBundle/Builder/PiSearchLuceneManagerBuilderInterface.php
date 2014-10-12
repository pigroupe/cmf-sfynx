<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   PiApp
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-06-11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Builder;

/**
 * PiSearchLuceneManagerBuilderInterface interface.
 *
 * @subpackage   PiApp
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface PiSearchLuceneManagerBuilderInterface
{
    public function renderSource($id, $lang = '', $params = null);
    public static function create($directory);
    public static function open($directory);
    public static function commit();
    public function contentPage($pathInfo, $Query = null, $MaxResultByWord = 5, $class = "", $MaxLimitCara = 0);
    public function indexPage(\Sfynx\CmfBundle\Entity\Page $page);
    public function deletePage(\Sfynx\CmfBundle\Entity\Page $page);
    public function searchPage($query, $options = null, $locale = '');
    public function searchPagesByQuery($query = "Key:*", $options = null);
}