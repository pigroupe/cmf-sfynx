<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage Tool
 * @package    Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-01-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Sfynx\ToolBundle\Util\PiArrayManager;

/**
 * Action Functions used in twig
 *
 * @subpackage Tool
 * @package    Extension
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiFormExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */    
    private $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getName()
    {
        return 'sfynx_tool_form_extension';
    }    
    
    /**
     * Returns a list of functions to add to the existing list.
     *
     * <code>
     *  {{ getService('sfynx.tool.string_manager').random(8) }}
     * </code>
     *
     * @return array An array of functions
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getFunctions()
    {
        return array(
            'group_form_errors'      => new \Twig_Function_Method($this, 'getFormErrors'),
            'group_form_view_errors' => new \Twig_Function_Method($this, 'getFormViewErrors')
        );
    }    

    /**
     * Get all error messages after binding form.
     *
     * @param Form   $form
     * @param string $type
     * @param string $delimiter
     * 	
     * @return array The list of all the errors
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */    
    public function getFormErrors(Form $form, $type = 'array', $delimiter = "<br />")
    {
    	$errors = array();
    	foreach ($form->getErrors() as $key => $error) {
            if($error->getMessagePluralization() !== null) {
                $errors[$key] = array(
                    'id'    => $error->getMessage(),
                    'trans' => $this->get('translator')->transChoice(
                        $error->getMessage(), 
                        $error->getMessagePluralization(), 
                        $error->getMessageParameters()
                    )
                );
            } else {
                $errors[$key] = array(
                    'id'    => $error->getMessage(),
                    'trans' => $this->get('translator')->trans($error->getMessage())
                );
            }    		
    	}
    	$all = $form->all();
    	if (is_array($all)) {
            foreach ($all as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child, 'array');
                }
            }
    	}
    	if ($type == 'array') {
            return $errors;
     	} else {
            return PiArrayManager::convertArrayToString($errors, $this->get('translator'), 'pi.form.label.field.', '', $delimiter);
     	}
    }
    
    /**
     * Displays form errors from the whole form and groups same messages into one.
     * Avoids displaying "Veuillez renseigner les champs en rouge" for each required field.
     * 
     * @param FormView $view
     * 	
     * @return array The list of all the errors
     * @access public
     */        
    public function getFormViewErrors(FormView $view)
    {
        $errors         = array_merge($view->vars['errors'], $this->getAllErrors($view));
        $uniqueErrors   = array();
        $setCheeseError = false;
        foreach ($errors as $i => $error) {
            if ($error->getMessageTemplate() == 'Vous devez choisir au moins un fromage.') {
                $setCheeseError = $error;
                continue;
            }
            if (!isset($uniqueErrors[$error->getMessageTemplate()])) {
                if (!($error->getMessageTemplate() == 'The captcha is invalid.' 
                        && isset($uniqueErrors['user.field_required']))
                ) {
                    $uniqueErrors[$error->getMessageTemplate()] = $error;
                }
            }
        }
        if ($setCheeseError) {
             $uniqueErrors['Vous devez choisir au moins un fromage.'] = $setCheeseError;
        }

        return array_values($uniqueErrors);
    }

    /**
     * Displays form errors from the whole form and groups same messages into one.
     * Avoids displaying "Veuillez renseigner les champs en rouge" for each required field.
     * 
     * @param FormView $view
     * 	
     * @return array The list of all errors
     * @access private
     */     
    private function getAllErrors(FormView $view)
    {
        $errors = array();
        if (count($view) > 0) {
            foreach ($view->children as $name => $child) {
                $errors = array_merge($errors, $this->getAllErrors($child));
            }

            return array_merge($view->vars['errors'], $errors);
        }

        return $view->vars['errors'];
    }    
}
