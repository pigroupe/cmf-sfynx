<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace Tms\Bundle\MediaClientBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator;
use Tms\Bundle\MediaClientBundle\Model\Media;

class TmsMediaUploadFileConstraintValidator extends FileValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof Media) {
            parent::validate($value->getUploadedFile(), $constraint);
        }
    }
}
