<?php
/**
 * This file is part of the <Admin> project.
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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Routes
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
class RoutesType extends AbstractType
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
    
        $attr = $view->vars['attr'];
        $view->vars['attr'] = $attr;
    }    

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
            
        $routeCollection = $this->container->get('router')->getRouteCollection();
        $routes = array();
        foreach ($routeCollection->all() as $name => $route) {
            $routes[$name] = $name; // $route->compile();
        }
        krsort($routes);
        
        $resolver->setDefaults(array(
                'choices' => function (Options $options, $parentChoices) use ($routes) {
                    return empty($parentChoices) ? $routes : array();
                },
        ));
    }
    
    public function getParent()
    {
        return 'choice';
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sfynx_routes';
    }    
}
