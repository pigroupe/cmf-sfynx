<?php
/**
 * This file is part of the <Core> project.
 *
 * @category   Core
 * @package    Form
 * @subpackage Handler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-03-09
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CoreBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Sfynx\CoreBundle\Form\Handler\FormHandlerInterface;

/**
 * A FormHandler is a object that is reponsable of form binding and post treatment
 * 
 * @category   Core
 * @package    Form
 * @subpackage Handler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractFormHandler implements FormHandlerInterface
{
    protected $form;
    protected $request;

    public function __construct(FormInterface $form, Request $request)
    {
        $this->form = $form;
        $this->request = $request;
    }

    /**
     * @return array Accepted request methods
     */
    abstract protected function getValidMethods();

    /**
     * This method implements the post-processing if the form is bound and valid.
     * The return value will be available as process() return (should be falsy on failure)
     *
     * @return mixed
     */
    abstract protected function onSuccess();

    /**
     * Validates if the request can be accepted
     */
    protected function validateRequest()
    {
        return array_search($this->request->getMethod(), $this->getValidMethods()) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function process()
    {
        if ($this->validateRequest()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                return $this->onSuccess();
            }
        }

        return false;
    }

    /**
     * Returns the current form
     *
     * @return Symfony\Component\Form\Form
     */
    public function getForm()
    {
        return $this->form;
    }
}
