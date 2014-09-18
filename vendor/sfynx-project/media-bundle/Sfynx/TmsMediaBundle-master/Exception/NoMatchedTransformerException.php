<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */
namespace Tms\Bundle\MediaBundle\Exception;

class NoMatchedTransformerException extends \Exception
{
    /**
     * The constructor.
     */
    public function __construct($format)
    {
        parent::__construct(sprintf(
            'No matched transformer for the given format: %s.',
            $format
        ));
    }
}
