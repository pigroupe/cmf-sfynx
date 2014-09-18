<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */
namespace Tms\Bundle\MediaBundle\StorageMapper\Rule;

use Tms\Bundle\MediaBundle\StorageMapper\Rule\AbstractRule;
use Tms\Bundle\MediaBundle\Exception\UnavailabeSizeUnitException;

abstract class AbstractCreatedRule extends AbstractRule
{
    /**
     * Convert a string to a DateTime
     *
     * @param string $from
     * @return DateTime The from converted
     */
    public static function convertToDateTime($from)
    {
        return new \DateTime($from);
    }
}
