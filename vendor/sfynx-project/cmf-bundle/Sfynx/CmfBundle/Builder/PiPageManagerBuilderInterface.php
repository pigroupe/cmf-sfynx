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

use Symfony\Component\HttpFoundation\Response as Response;

/**
 * PiPageManagerBuilderInterface interface.
 *
 * @subpackage   PiApp
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface PiPageManagerBuilderInterface
{
    public function setPageById($idPage);
    public function setPageByParams($url, $slug, $isSetPage = false);
    public function setPageByRoute($route = '', $isSetPage = false);
    
    public function render($lang = '', $isSetPage = false);
    public function renderSource($id, $lang = '', $params = null);    
    public function renderESISource($serviceName, $method, $id, $lang = '', $params = null, $options = null, Response $response = null);
    
    public function redirectPage($route_name = 'error_404');
    public function copyPage($locale = '');
    
    public function cacheRefresh();
    public function cacheTreeChartPageRefresh();
    
    public function setTreeWithPages($htmlTree);
    public function setHomePage($htmlTree);
    public function setNode($htmlTree);
    
    public function getChildrenHierarchyRub();
    public function getUrlByPage(\Sfynx\CmfBundle\Entity\Page $page, $type = '');
    public function getUrlByType($type, $entity = null);
    public function getPageMetaInfo($lang = '', $title = '', $description = '', $keywords = '');
    
}