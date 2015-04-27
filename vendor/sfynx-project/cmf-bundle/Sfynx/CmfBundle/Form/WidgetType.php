<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Form
 * @package    CMS_Form
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-02-10
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sfynx\CmfBundle\Twig\Extension\PiWidgetExtension;

/**
 * Description of the TranslationPageType form.
 *
 * @subpackage   Admin_Form
 * @package    CMS_Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class WidgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
            ->add('enabled', 'checkbox', array(
                    //'data'  => true,
                    'label'    => 'pi.form.label.field.enabled',
            ))
            ->add('secure', 'checkbox', array(
            		'label'    => 'pi.page.form.secure',
            		'required' => false,
            ))
            ->add('heritage', 'sfynx_security_roles', array(
            		'multiple' => true,
            		'required' => false,
            		'label'    => 'pi.page.form.heritage',
            		"attr" => array(
            				"class"=>"pi_multiselect",
            		),
            ))            
            ->add('plugin', 'choice', array(
                    'choices'   => PiWidgetExtension::getAvailableWidgetPlugins(),
                    'required'  => true,
                    'multiple'    => false,
                    'expanded'  => false,
            ))
            ->add('action')
            ->add('configCssClass')
            ->add('configXml', 'textarea', array(
                    //'data'  => PiWidgetExtension::getDefaultConfigXml(),
            ))
            ->add('position')
        ;

        $builder
            ->add('cacheable', 'checkbox', array(
                    'label'     => 'pi.page.form.cacheable',
                    'required'  => false,
                    'help_block' => 'pi.page.form.field.cacheable',
                    "label_attr" => array(
 						"class"=>"widget_httpcache",
 					)
            ))
            ->add('public', 'checkbox', array(
                    'label'     => 'pi.page.form.public',
                    'required'  => false,
                    'help_block' => 'pi.page.form.field.public',
                    "label_attr" => array(
                    		"class"=>"widget_httpcache",
                    )
            ))
            ->add('lifetime', 'number', array(
                    'label'     => 'pi.page.form.lifetime',
                    'required'  => false,
                    'help_block' => 'pi.page.form.field.lifetime',
                    "label_attr" => array(
                    		"class"=>"widget_httpcache",
                    )
            ))
            ->add('cacheTemplating', 'choice', array(
            		'choices'   => \Sfynx\CmfBundle\Repository\WidgetRepository::getAvailableCacheTemplating(),
            		'label'    => 'pi.widget.form.cachetemplating',
            		'required'  => true,
            		'multiple'    => false,
            		'expanded' => true,
            		"label_attr" => array(
            				"class"=>"widget_behavior widget_cachetemplating",
            		),
            ))
            ->add('sluggify', 'choice', array(
            		'choices'   => \Sfynx\CmfBundle\Repository\WidgetRepository::getAvailableSluggify(),
            		'label'    => 'pi.widget.form.sluggify',
            		'required'  => true,
            		'multiple'    => false,
            		'expanded' => true,
            		"label_attr" => array(
            				"class"=>"widget_behavior widget_sluggify",
            		),
            ))            
            ->add('ajax', 'choice', array(
            		'choices'   => \Sfynx\CmfBundle\Repository\WidgetRepository::getAvailableAjax(),
            		'label'    => 'pi.widget.form.ajax',
            		'required'  => true,
            		'multiple'    => false,
            		'expanded' => true,
                    "label_attr" => array(
                    		"class"=>"widget_behavior widget_ajax",
                    ),
            ))                        
        ;
    }

    public function getName()
    {
        return 'piapp_adminbundle_widgettype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Sfynx\CmfBundle\Entity\Widget',
        ));
    }    
}
