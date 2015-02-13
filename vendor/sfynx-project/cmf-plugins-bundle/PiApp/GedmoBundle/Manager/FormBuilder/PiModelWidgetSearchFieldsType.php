<?php
/**
 * This file is part of the <Gedmo> project.
 *
 * @category   Gedmo_Managers
 * @package    FormBuilder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2014-08-31
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\GedmoBundle\Manager\FormBuilder;  

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of the Form builder manager
 *
 * @category   Gedmo_Managers
 * @package    FormBuilder
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiModelWidgetSearchFieldsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nameField', 'text', array(
        		'label'    => "Name field",
        ));
        $builder->add('valueField', 'text', array(
                'label'    => "Value field",
                'data' => 'IS NOT NULL, LIKE "%a%"',
        ));
        
    }

    public function getName()
    {
        return 'piapp_adminbundle_enquirytype';
    }
}