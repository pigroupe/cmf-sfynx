<?php

/**
 * This file is part of the <Admin> project.
 *
 * @category   Bundle
 * @package    PiApp
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2014-07-23
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

/**
 * Response event of connection user.
 *
 * @category   Admin_Eventlistener
 * @package    EventListener
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ResponseEvent extends Event
{
    /**
     * @var \Symfony\Component\HttpFoundation\Response $response
     */
    private $response;
    
    /**
     * @var \DateTime $dateExpire
     */
    private $dateExpire;    

    public function __construct( Response $response, $dateExpire = 0)
    {
        $this->response   = $response;
        $this->dateExpire = $dateExpire;
    }

    /**
     * @return  response
     */
    public function getResponse() 
    {
        return $this->response;
    }
    
    /**
     * @return  void
     */
    public function setResponse(Response $response)
    {
    	$this->response = $response;
    }
    
    /**
     * @return  dateExpire
     */
    public function getDateExpire()
    {
    	return $this->dateExpire;
    }
    
    /**
     * @return  void
     */
    public function setDateExpire(\DateTime $dateExpire)
    {
    	$this->dateExpire = $dateExpire;
    }    
}
