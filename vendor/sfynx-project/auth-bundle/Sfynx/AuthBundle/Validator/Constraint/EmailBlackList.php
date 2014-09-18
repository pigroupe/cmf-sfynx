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

/**
 * @Annotation
 */
class EmailBlackList extends Constraint
{
    public $message = 'Les services de mails jetables ne sont pas autorisés.';

    public function validatedBy()
    {
        return 'email_black_list';
    }
}
