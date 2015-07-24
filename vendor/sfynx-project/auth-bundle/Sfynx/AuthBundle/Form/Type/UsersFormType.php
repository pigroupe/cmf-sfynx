<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Auth
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
namespace Sfynx\AuthBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints;

/**
 * Security Roles
 *
 * @category   Auth
 * @package    Form
 * @subpackage Type
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class UsersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('enabled', 'checkbox', array(
            'data'  => true,
            'label'	=> 'pi.form.label.field.enabled',
        ))      
        ->add('username', 'text', array(
            'label' => 'pi.form.label.field.username',
        ))
        ->add('email', 'email', array(
            'label' => 'pi.form.label.field.email',
        ))
        ->add('langCode', 'entity', array(
            'class' => 'SfynxAuthBundle:Langue',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('k')
                    ->select('k')
                    ->where('k.enabled = :enabled')
                    ->orderBy('k.label', 'ASC')
                    ->setParameter('enabled', 1);
            },
            'property' => 'label',
            "label"    => "pi.form.label.field.language",
            "attr" => array(
                            "class"=>"pi_simpleselect",
            ),
        ))     
        ->add('name', 'text', array(
            'label' => 'pi.form.label.field.name',
        ))
        ->add('nickname', 'text', array(
            'label' => 'pi.form.label.field.nickname',
        ))
        ->add('groups','entity', array(
            'label' => 'pi.form.label.field.usergroup',
            'class' => 'SfynxAuthBundle:Group',
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('k')
                ->select('k')
                ->where('k.enabled = :enabled')
                ->orderBy('k.name', 'ASC')
                ->setParameter('enabled', 1);
            },
            'property' => 'name',
            'multiple'	=> true,
            'expanded'  => false,
            'required'  => true,
        ))
        ->add('permissions', 'sfynx_security_permissions', array( 'multiple' => true, 'required' => false))
        ->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.password'),
            'second_options' => array('label' => 'form.password_confirmation'),
            'invalid_message' => 'fos_user.password.mismatch',
        ))            
      ; 	
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'validation_groups' => array('registration'),
            )
        );
    }

    /**
     * {@inheritdoc}
     */	
    public function getName()
    {
        return 'user_from';
    }
}
