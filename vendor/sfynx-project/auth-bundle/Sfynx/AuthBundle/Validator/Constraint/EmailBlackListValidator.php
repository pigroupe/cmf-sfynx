<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Sfynx
 * @package    Bundle
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EmailBlackListValidator extends ConstraintValidator
{
    private $blackList;

    public function setBlackList(array $blackList)
    {
        $this->blackList = $blackList;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $domainArray = preg_split("/@/", $value);
        if (count($domainArray) > 1) {
            $domain = $domainArray[1];

            if (!is_null($this->blackList) && in_array($domain, $this->blackList)) {
                $this->context->addViolation($constraint->message);
            }
        }
    }
}
