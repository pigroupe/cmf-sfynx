<?php

/**
 * This file is part of the <Auth> project.
 *
 * @subpackage Object
 * @package    Event
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2014-07-23
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sfynx\AuthBundle\Entity\User;

/**
 * Response event of connection user.
 *
 * @subpackage Object
 * @package    Event
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class ResponseEvent extends Event
{
    /**
     * @var Request $request
     */
    private $request;
    
    /**
     * @var Response $response
     */
    private $response;
    
    /**
     * @var \DateTime $dateExpire
     */
    private $dateExpire; 
    
    /**
     * @var User $user
     */
    private $user;    
    
    /**
     * @var string $locale
     */
    private $locale;    
    
    /**
     * @var string $redirect Route name value of the redirection url response
     */
    private $redirect;        

    
    public function __construct($response, $dateExpire = 0, $request = null, $user = null, $locale = '')
    {
        $this->response   = $response;
        $this->dateExpire = $dateExpire;
        $this->user       = $user;
        $this->request    = $request;
        $this->locale     = $locale;
    }

    /**
     * @return Response
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
    
    /**
     * @return Request
     */
    public function getRequest() 
    {
        return $this->request;
    }
    
    /**
     * @return  void
     */
    public function setRequest(Request $request)
    {
    	$this->request = $request;
    }    
    
    /**
     * @return User
     */
    public function getUser()
    {
    	return $this->user;
    }
    
    /**
     * @return  void
     */
    public function setUser(User $user)
    {
    	$this->user = $user;
    }   
    
    /**
     * @return locale
     */
    public function getLocale()
    {
    	return $this->locale;
    }
    
    /**
     * @return  void
     */
    public function setLocale($locale)
    {
    	$this->locale = $locale;
    }   
    
    /**
     * @return redirect
     */
    public function getRedirect()
    {
    	return $this->redirect;
    }
    
    /**
     * @return  void
     */
    public function setRedirect($route_name)
    {
    	$this->redirect = $route_name;
    }     
}
