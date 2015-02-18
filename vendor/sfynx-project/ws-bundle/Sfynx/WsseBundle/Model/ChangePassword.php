<?php

namespace NosBelIdees\UserBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @author Riad HELLAL <hellal.riad@gmail.com>
 * @package NosBelIdeesUserBundle
 */
class ChangePassword
{
    /**
     * @Assert\Length(min=8, max=50, minMessage="user.plain_password.min_length", maxMessage="user.plain_password.max_length")
     * @Assert\NotBlank(message="user.field_required")
     */
    public $new;
}
