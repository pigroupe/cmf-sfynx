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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Sfynx\AuthBundle\Entity\User;
use Sfynx\CmfBundle\Entity\Tag;

/**
 * Description of the PageByTransType form.
 *
 * @subpackage   Admin_Form
 * @package    CMS_Form
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PageByTransType extends AbstractType 
{
    /**
     * @var array
     */
    protected $_roles_user;
    
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $_container;    
    
    /**
     * @var string
     */
    protected $_locale;    
    
    /**
     * Constructor.
     *
     * @param array $roles_user
     * @return void
     */
    public function __construct(ContainerInterface $container, $locale = '', $roles_user = array('ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_CONTENT_MANAGER'))
    {
        $this->setInit($container, $locale, $roles_user);
    }
    
    public function setInit($container, $locale = '', $roles_user = array()) {
        $this->_container  = $container;
        if (is_array($roles_user) && count($roles_user) == 0) {
            $User   = $this->_container->get('security.context')->getToken()->getUser();
            $role_ser = $User->getRoles();
        }
        $this->setRolesUser($roles_user);
        if (empty($locale)) {
            $locale = $this->_container->get('request')->getLocale();
        }       
        $this->setLocale($locale);
    }
    
    public function setRolesUser($roles_user) {
        $this->_roles_user = $roles_user;
    }
    
    public function setLocale($locale) {
        $this->_locale     = $locale;
    }    
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $array_tags = null;
        if ($builder->getData()->getTranslations() instanceof \Doctrine\ORM\PersistentCollection){
            $array_tags = array();
            foreach($builder->getData()->getTranslations() as $translation){
                if ($translation->getTags() instanceof \Doctrine\ORM\PersistentCollection) {
                    foreach ($translation->getTags() as $tag) {
                        if ($tag instanceof Tag){
                            array_push($array_tags, $tag->getId());
                        }
                    }
                }
            }
        }
        if (isset($_POST['piapp_adminbundle_pagetype']['translations'])) {
            $array_tags =array();
            foreach ($_POST['piapp_adminbundle_pagetype']['translations'] as $translations) {
                if (isset($translations['tags'])) {
                    foreach ($translations['tags'] as $tag) {
                        array_push($array_tags, $tag);
                    }
                }
            }
        }         
        $_POST['_cmfpage_translations_tags_'] = $array_tags;
        //
        $id_users = null;
        if ($builder->getData()->getUser()
                instanceof User
        ) {
            $id_users = $builder->getData()->getUser()->getId();
        }
        if (isset($_POST['piapp_adminbundle_pagetype']['user'])) {
            $id_users = $_POST['piapp_adminbundle_pagetype']['user'];
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
        ;
                
        if (!empty($options) 
                && array_key_exists('rubrique_show', $options) 
                && $options['rubrique_show']
        ) {
            $builder   
                ->add('rubrique', 'entity', array(
                        'class' => 'SfynxCmfBundle:Rubrique',
                        'query_builder' => function(EntityRepository $er) {
                            return $er->getAllPageRubrique();
                        },
                        'property' => 'titre',
                        'empty_value' => 'pi.form.label.select.choose.option',
                        'label'     => 'pi.page.form.rubrique',
                        'multiple'    => false,
                        'required'  => false,
                        "attr" => array(
                            "class"=>"pi_simpleselect",
                        ),                    
                ))
            ;    
        }
                            
        if (!empty($options) 
                && array_key_exists('layout_show', $options) 
                && $options['layout_show']
        ) {
            $builder                              
                ->add('layout', 'entity', array(
                        'class' => 'SfynxAuthBundle:Layout',
                        'label'     => 'pi.page.form.layout',
                        "attr" => array(
                                "class"=>"pi_simpleselect",
                        ),
                ))

            ;
        }
        
        if (!empty($options) 
                && array_key_exists('page_css_show', $options) 
                && $options['page_css_show']
        ) {
            $builder          
                ->add('page_css', 'entity', array(
                        'class' => 'SfynxCmfBundle:Page',
                        'query_builder' => function(EntityRepository $er) {
                            return $er->getAllPageCss();
                        },
                        'property' => 'url',
                        'multiple'    => true,
                        'required'  => false,
                        'empty_value' => 'pi.form.label.select.choose.option',
                        'label'     => 'pi.page.form.page_css',
                        "attr" => array(
                                "class"=>"pi_multiselect",
                        ),
                ))   
            ;
        }
                    
        if (!empty($options) 
                && array_key_exists('page_js_show', $options) 
                && $options['page_js_show']
        ) {
            $builder                      
                ->add('page_js', 'entity', array(
                        'class' => 'SfynxCmfBundle:Page',
                        'query_builder' => function(EntityRepository $er) {
                            return $er->getAllPageJs();
                        },
                        'property' => 'url',
                        'multiple'    => true,
                        'required'  => false,
                        'empty_value' => 'pi.form.label.select.choose.option',
                        'label'     => 'pi.page.form.page_js',
                        "attr" => array(
                                "class"=>"pi_multiselect",
                        ),
                ))
            ; 
        }
                    
        $builder                    
            ->add('keywords', 'entity', array(
                    'class' => 'SfynxCmfBundle:KeyWord',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->getAllPageKeyWords();
                    },
                    'multiple'    => true,
                    'required'  => false,
                    'empty_value' => 'pi.form.label.select.choose.option',
                    'label'     => 'pi.page.form.keywords',
                    "attr" => array(
                            "class"=>"pi_multiselect",
                    ),
            ))      
            ->add('meta_content_type', 'hidden')
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
        ;                            
        
                    
        if (!empty($options) 
                && array_key_exists('route_name_show', $options) 
                && $options['route_name_show']
        ) {
            $builder                    
                ->add('route_name', 'text', array(
                        'label'    => 'pi.page.form.route_name'
                ))
            ;
        }
            
        $builder                    
            ->add('url', 'text', array(
                    'label'    => 'pi.page.form.url'
            ))
        ;
                    
        if (!empty($options) 
                && array_key_exists('translations_show', $options) 
                && $options['translations_show']
        ) {
            $builder
                ->add('translations', 'collection', array(
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype'    => true,
                        // Post update
                        'by_reference' => true,                    
                        'type'   => new TranslationPageType($this->_locale, $this->_container),
                        'options'  => array(
                            'attr'      => array('class' => 'translation_widget')
                        ),    
                        'label'    => ' '
                ))
            ;
        }                    
                    
        if (!empty($options) 
                && array_key_exists('blocks_show', $options) 
                && $options['blocks_show']
        ) {
            $builder
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
    }

    public function getName()
    {
        return 'piapp_adminbundle_pagetype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Sfynx\CmfBundle\Entity\Page',
                'rubrique_show' => true,
                'translations_show' => true,
                'blocks_show' => false,                
                'layout_show' => true,
                'page_css_show' => true,
                'page_js_show' => true,
                'route_name_show' => true,
        ));
    }    
}
