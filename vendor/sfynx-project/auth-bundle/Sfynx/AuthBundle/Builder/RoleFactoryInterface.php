<?php
/**
 * This file is part of the <Tool> project.
 *
 * @category   User
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-23
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\Builder;

/**
 * RoleFactory interface.
 *
 * @category   User
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface RoleFactoryInterface
{
    public function getNoAuthorizeRoles($heritage);
    public function isJsonFileExisted();
    public function setJsonFileRoles();
    public function getAllUserRoles();
    public function getBestRoleUser();
    public function getBestRoles($ROLES);
    public function getAllHeritageByRoles($ROLES);
    public function buildRoleMap();
}