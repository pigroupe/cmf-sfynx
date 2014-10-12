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
 * PiWidgetManagerBuilderInterface interface.
 *
 * @subpackage   PiApp
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface PiWidgetManagerBuilderInterface
{
    public function exec($id, $lang = "");
    public function render($lang = '');
    public function renderSource($id, $lang = '', $params = null);
    public function renderCache($serviceName, $tag, $id, $lang, $params = null);
    public function renderService($serviceName, $id, $lang, $params = null);
    public function renderJquery($JQcontainer, $id, $lang, $params = null);
    public function setScript();
    public function setInit();
}