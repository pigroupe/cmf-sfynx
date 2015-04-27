<?php
/**
 * This file is part of the <Cmf> project.
 *
 * @subpackage   Admin_Form
 * @package    CMS_Form
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-07
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CmfBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sfynx\CmfBundle\Repository\PageRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sfynx\AuthBundle\Entity\User;

/**
 * Description of the PageByBlockType form.
 *
 * @subpackage   Admin_Form
 * @package    CMS_Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PageByBlockType extends AbstractType
{

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $_container;
    
    /**
     * @var array
     */
    protected $_roles_user;
    
    /**
     * Constructor.
     *
     * @param array $roles_user
     * @return void
     */
    public function __construct(ContainerInterface $container, $roles_user = array('ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_CONTENT_MANAGER'))
    {
        $this->_roles_user = $roles_user;
        $this->_container     = $container;
    }
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id_users = null;
        if ($builder->getData()->getUser()
                instanceof User
        ) {
            $id_users = $builder->getData()->getUser()->getId();
        }
        //
        if (in_array('ROLE_ADMIN', $this->_roles_user) 
                || in_array('ROLE_SUPER_ADMIN', $this->_roles_user) 
                || in_array('ROLE_CONTENT_MANAGER', $this->_roles_user)
        ) {
            $read_only = false;
        } else {
            $read_only = true;
        }
                
        $builder
            ->add('enabled', 'checkbox', array(
                    'data'  => true,
                    'label'    => 'pi.form.label.field.enabled',
            ))
            ->add('user', 'entity', array(
                    'class' => 'SfynxAuthBundle:User',
                    'query_builder' => function(EntityRepository $er) use ($id_users) {
                        $translatableListener = $this->_container->get('gedmo.listener.translatable');
                        $translatableListener->setTranslationFallback(true);
                        return $er->createQueryBuilder('a')
                            ->select('a')
                            ->where("a.id IN (:id)")
                            ->andWhere('a.enabled = 1')
                            ->setParameter('id', $id_users);
                    },
                    'empty_value' => 'pi.form.label.select.choose.user',
                    'label'    => "pi.form.label.field.user",
                    'multiple'    => false,
                    'required'  => false,
                    "attr" => array(
                        "class"=>"pi_simpleselect ajaxselect", // ajaxselect
                        "data-url"=>$this->_container->get('sfynx.tool.route.factory')->getRoute("users_selectentity_ajax"),
                        "data-selectid" => $id_users,
                        "data-max" => 50,
                    )                           
            ))  
            ->add('rubrique', 'entity', array(
                    'class' => 'SfynxCmfBundle:Rubrique',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->getAllPageRubrique();
                    },
                    'empty_value' => 'Choose an option',
                    'multiple'    => false,
                    'required'  => false,
                    "attr" => array(
                            "class"=>"pi_simpleselect",
                    ),                    
            ))
            ->add('layout', 'entity', array(
                    'class' => 'SfynxAuthBundle:Layout',
                    "attr" => array(
                            "class"=>"pi_simpleselect",
                    ),
            ))
            ->add('page_css', 'entity', array(
                    'class' => 'SfynxCmfBundle:Page',
                    'query_builder' => function(EntityRepository $er) {
                            return $er->getAllPageCss();
                    },
                    'property' => 'url',
                    'multiple'    => true,
                    'required'  => false,
                    "attr" => array(
                            "class"=>"pi_multiselect",
                    ),
            ))
            ->add('page_js', 'entity', array(
                    'class' => 'SfynxCmfBundle:Page',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->getAllPageJs();
                    },
                    'property' => 'url',
                    'multiple'    => true,
                    'required'  => false,
                    "attr" => array(
                            "class"=>"pi_multiselect",
                    ),
            ))            
            ->add('keywords', 'entity', array(
                    'class' => 'SfynxCmfBundle:KeyWord',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->getAllPageKeyWords();
                    },
                    'multiple'    => true,
                    'required'  => false,
                    "attr" => array(
                            "class"=>"pi_multiselect",
                    ),
            ))        
            ->add('meta_content_type', 'choice', array(
                    'choices'   => PageRepository::getAvailableContentTypes(),
                    'required'  => true,
                    'multiple'    => false,
                    'expanded'  => true,
                    'read_only'    => true,
            ))
            ->add('cacheable', 'checkbox', array(
                    'label'     => 'pi.page.form.cacheable',
                    'required'  => false,
                    //'help_block' => 'Returns a 304 "not modified" status, when the template has not changed since last visit.',
                    'help_block' => $this->_container->get('translator')->trans('pi.page.form.field.cacheable'),
            ))
            ->add('public', 'checkbox', array(
                    'label'     => 'pi.page.form.public',
                    'required'  => false,
                    //'help_block' => 'Allows proxies to cache the same content for different visitors.'
                    'help_block' => $this->_container->get('translator')->trans('pi.page.form.field.public'),
            ))
            ->add('lifetime', 'number', array(
                    'label'     => 'pi.page.form.lifetime',
                    'required'  => false,
                    //'help_block' => 'Does a full content caching during the specified lifetime. Leave empty for no cache.'
                    'help_block' => $this->_container->get('translator')->trans('pi.page.form.field.lifetime'),
            ))
            ->add('route_name', 'text', array(
                    'label'    => 'pi.page.form.route_name'
            ))
            ->add('url', 'text', array(
                    'label'    => 'pi.page.form.url'
            ))
            ->add('blocks', 'collection', array(
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype'    => true,
                    // Post update
                    'by_reference' => true,                    
                    'type'   => new BlockType,
                    'options'  => array(
                        'attr'      => array('class' => 'block_widget')
                    ),    
                    'label'    => ' '
            )) 
         ;
    }

    public function getName()
    {
        return 'piapp_adminbundle_pagetype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Sfynx\CmfBundle\Entity\Page',
        ));
    }     
}
