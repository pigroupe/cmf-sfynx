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

use Sfynx\CmfBundle\Entity\Page;
use Sfynx\CmfBundle\Entity\Widget;
use Sfynx\CmfBundle\Entity\TranslationWidget;

/**
 * PiCoreManagerBuilderInterface interface.
 *
 * @subpackage   PiApp
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface PiCoreManagerBuilderInterface
{
    public function run($tag, $id, $lang, $params = null);
    public function render($lang = '');
    public function renderSource($id, $lang = '', $params = null);
    public function cacheRefreshByname($name);
    
    public function getPageById($idpage);
    public function getPageByRoute($route, $isForce = false);
    public function getBlocksByPageId($idpage);
    public function getWidgetById($idWidget);
    public function getBlockById($idBlock);
    public function getTranslationByPageId($idpage);
    public function getTranslationByWidgetId($idwidget, $lang = '');
    public function getResponseByIdAndType($type, $id);
    public function getCurrentPage();
    public function setCurrentPage(Page $page = null);
    public function setCurrentWidget(Widget $widget = null);
    public function getCurrentWidget();
    public function getCurrentTransWidget();
    public function setCurrentTransWidget(TranslationWidget $transWidget = null);
    public function parseTemplateParam($RenderResponseParam);
    public function getScript($script, $type = 'string');
    
    public function createJsonFileName($type, $id, $lang = '');
    public function setJsonFileEtag($tag, $id, $lang, $params = null);
    
    public function getPageMetaInfo($lang = '', $title = '', $description = '', $keywords = '', $pathInfo = "");
    public function isSluggifyPage($pathInfo = "");
}