<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */
namespace Tms\Bundle\MediaBundle\Exception;

class MediaAlreadyExistException extends \Exception
{
    /**
     * The constructor.
     */
    public function __construct()
    {
        parent::__construct('The media already exist.');
    }
}
