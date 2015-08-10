<?php 
/**
 * This file is part of the <CmfPluginsGedmo> project.
 *
 * @category   CmfPluginsGedmo
 * @package    Entity
 * @subpackage ModelTranslation
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\GedmoBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Sfynx\CoreBundle\Model\AbstractTranslationEntity;

/**
 * @ORM\Entity(repositoryClass="PiApp\GedmoBundle\Repository\MenuRepository")
 * @ORM\Table(
 *         name="gedmo_menu_translations",
 *         uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx_sfynx_trans_menu", columns={
 *             "locale", "object_id", "field"
 *         })}
 * )
 * 
 * @category   CmfPluginsGedmo
 * @package    Entity
 * @subpackage ModelTranslation
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class MenuTranslation extends AbstractTranslationEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="PiApp\GedmoBundle\Entity\Menu", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
    
    /**
     * Convinient constructor
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public function __construct($locale = null, $field = null, $value = null)
    {
        if (!is_null($locale))
            $this->setLocale($locale);
        if (!is_null($field))
            $this->setField($field);
        if (!is_null($value))
            $this->setContent($value);
    }    
        
}