<?php
/**
 * This file is part of the <Admin> project.
 *
 * @subpackage   Tool
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2013-03-26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Builder;

/**
 * Rest Manager Interface.
 *
 * @subpackage   Tool
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface PiRestManagerInterface
{
    public function setUrl ($pUrl);
    public function get ($pGetParams = array());
    public function post ($pGetParams = array(), $pPostParams=array());
    public function put ($pGetParams = array(), $pContent = null);
    public function delete ($pGetParams = array(), $pContent = null);
}
