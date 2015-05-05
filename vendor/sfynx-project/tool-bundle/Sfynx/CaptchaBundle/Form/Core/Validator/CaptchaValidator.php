<?php
/*
 * This file is part of the <Captcha> project.
 *
 * @category   Captcha
 * @package    Form
 * @subpackage Validator
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
namespace Sfynx\CaptchaBundle\Form\Core\Validator;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

use Sfynx\CaptchaBundle\Manager\Type\Captcha;

/**
 * CaptchaValidator
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class CaptchaValidator implements EventSubscriberInterface
{
    private $captcha;

    /**
     * Constructs
     *
     * @param Captcha $captcha
     */
    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (
            $this->captcha->getCode() !== $this->captcha->encode($data)
        ) {
            $form->addError(new FormError('The captcha is invalid.'));
        }

        $this->captcha->removeCode();
    }

    /**
     * {@inheritdoc}
     */    
    public static function getSubscribedEvents()
    {
        return array(FormEvents::POST_SUBMIT => 'validate');
    }
}
