<?php
/**
 * This file is part of the <web service> project.
 *
 * @subpackage WS
 * @package Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2013-03-26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\WsBundle\Extension;

use Sfynx\WsBundle\Helper\AuthHelper;

/**
 * Authentication Extension
 *
 * @subpackage WS
 * @package Extension
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class AuthExtension extends \Twig_Extension
{

    /**
     * @var \Sfynx\WsBundle\Helper\AuthHelper
     */
    private $Helper;

    /**
     * Constructor.
     *
     * @param \Sfynx\WsBundle\Helper\AuthHelper $Helper
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function __construct(AuthHelper $Helper)
    {
        $this->Helper = $Helper;
    }

    /**
     * Returns the helper of the extension.
     *
     * @return array The helper of the extension
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getGlobals()
    {
        return array(
            'ws_auth' => $this->Helper
        );
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
        return 'ws_auth';
    }

}