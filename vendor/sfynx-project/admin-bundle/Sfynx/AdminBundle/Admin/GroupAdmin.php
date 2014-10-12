<?php
/**
 * This file is part of the <Admin> project.
 * 
 * @subpackage   Sfynx
 * @package    sonataCRUD
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-11-17
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Validator\ErrorElement;

/**
 * Group Sonata Admin Controle
 *
 * @subpackage   Sfynx
 * @package    sonataCRUD
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class GroupAdmin extends Admin
{
    protected $translationDomain    = 'group';
    
    protected $baseRoutePattern        = '/group';

    /**
     * {@inheritdoc}
     */    
    public function getNewInstance()
    {
        $class = $this->getClass();

        return new $class('', array());
    }
    
    /**
     * {@inheritdoc}
     */    
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
        ->add('name')
        ->with('Roles')
            ->add('roles', 'array')
        ->end();
    }
        
    /**
     * {@inheritdoc}
     */    
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('roles')
            ->add('permissions')
            ->add('_action', 'actions', array( 'actions' => array(  
                     'edit'   => array(),
                     'view'   => array(),
                     'delete' => array(),
                     // autre action specifique ::: 'unpublish' => array('template' => 'MyBundle:Admin:action_unpublish.html.twig'),
                    ))
                );
    }

    /**
     * {@inheritdoc}
     */    
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
        ;
    }
    
    /**
     * {@inheritdoc}
     */    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('name', null, array('required' => true, 'label' => $this->trans('field.group.name') ))
            ->end()
            ->with('Roles')
                ->add('roles', 'sfynx_security_roles', array( 'multiple' => true, 'required' => false, 'expanded' => true))
                ->setHelps(array(
                    'roles' => $this->trans('help.role.name')
                ))
            ->end()
            ->with('Permissions')
                ->add('permissions', 'sfynx_security_permissions', array( 'multiple' => true, 'required' => false, 'expanded' => true))
            ->end()
        ;
    }    
    
    /**
     * @param \Sonata\AdminBundle\Validator\ErrorElement $errorElement
     * @param $object
     * @return void
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('name')
                ->assertNotNull()
                ->assertNotBlank()
                ->assertMaxLength(array('limit' => 25))
            ->end()
        ;
    }        
    
}