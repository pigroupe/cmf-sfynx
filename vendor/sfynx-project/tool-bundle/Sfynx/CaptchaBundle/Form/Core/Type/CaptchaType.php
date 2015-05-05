<?php
/*
 * This file is part of the <Captcha> project.
 *
 * @category   Captcha
 * @package    Form
 * @subpackage Type
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
namespace Sfynx\CaptchaBundle\Form\Core\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sfynx\CaptchaBundle\Manager\Type\Captcha;
use Sfynx\CaptchaBundle\Form\Core\Validator\CaptchaValidator;

/**
 * CaptchaType
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class CaptchaType extends AbstractType
{
    private $captcha;
    private $options;

    /**
     * Constructs
     *
     * @param Captcha $captcha
     * @param array   $options
     */
    public function __construct(Captcha $captcha, array $options = array())
    {
        $this->captcha = $captcha;
        $this->options = $options;      
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->captcha->setOptions($this->options);

        $builder
            ->addEventSubscriber(new CaptchaValidator($this->captcha))
            ->setAttribute('captcha', $this->captcha)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /* $this->captcha->setOptions($this->options); */
        
        $view->vars = array_replace($view->vars, array(
            'value'     => '',
            'picture_name' => $this->captcha->getName(),
            'pictures_all' => $this->captcha->getPictures(false),
            'pictures_all_secure' => $this->captcha->getPictures(true)
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = array_merge(
            array('mapped' => false), // important to disconnect the captcha field with the entity
            $this->options
        );
        $resolver->setDefaults($defaults);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'hidden';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sfynx_captcha';
    }
}
