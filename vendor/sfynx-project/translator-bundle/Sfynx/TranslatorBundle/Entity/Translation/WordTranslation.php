<?php 
/**
 * This file is part of the <Translator> project.
 *
 * @category   Translator
 * @package    Entity
 * @subpackage ModelTranslation
 * @author     Riad HELLAL <hellal.riad@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\TranslatorBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity(repositoryClass="Sfynx\TranslatorBundle\Repository\WordRepository")
 * @ORM\Table(
 *         name="pi_word_translations",
 *         uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx_sfynx_trans_word", columns={
 *             "locale", "object_id", "field"
 *         })}
 * )
 * 
 * @category   Translator
 * @package    Entity
 * @subpackage ModelTranslation
 * @author     Riad HELLAL <hellal.riad@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class WordTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="Sfynx\TranslatorBundle\Entity\Word", inversedBy="translations")
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