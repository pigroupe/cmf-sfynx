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
use Tms\Bundle\MediaBundle\Exception\InvalidSizeRuleException;

abstract class AbstractSizeRule extends AbstractRule
{
    /**
     * Convert a string into Bytes
     *
     * @param string $from
     * @return integer The from converted
     */
    public static function convertToBytes($from)
    {
        $number = substr($from, 0, -2);
        $bytesMap = array("KB" => 1, "MB" => 2, "GB" => 3, "TB" => 4, "PB" => 5);
        $unit = strtoupper(substr($from, -2));

        if(!isset($bytesMap[$unit])) {
            throw new InvalidSizeRuleException($from);
        }

        return $number * pow(1024, $bytesMap[$unit]);
    }
}
