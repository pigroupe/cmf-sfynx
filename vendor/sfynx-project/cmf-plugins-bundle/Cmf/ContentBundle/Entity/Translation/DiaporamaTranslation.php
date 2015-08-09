<?php 
/**
 * This file is part of the <CmfPluginsContent> project.
 *
 * @category   CmfPluginsContent
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
namespace Cmf\ContentBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Sfynx\CoreBundle\Model\AbstractTranslationEntity;

/**
 * @ORM\Entity(repositoryClass="Cmf\ContentBundle\Repository\DiaporamaRepository")
 * @ORM\Table(
 *         name="cont_diaporama_translations",
 *         uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx_sfynx_trans_cmfplugin_diaporama", columns={
 *             "locale", "object_id", "field"
 *         })}
 * )
 * 
 * @category   CmfPluginsContent
 * @package    Entity
 * @subpackage ModelTranslation
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class DiaporamaTranslation extends AbstractTranslationEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Cmf\ContentBundle\Entity\Diaporama", inversedBy="translations")
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