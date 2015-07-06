<?php
/*
 * This file is part of the <Captcha> project.
 *
 * @category   Captcha
 * @package    Manager
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
namespace Sfynx\CaptchaBundle\Manager\Type;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Sfynx\CaptchaBundle\Manager\CaptchaInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Captcha implements CaptchaInterface
{
    protected $key;
    protected $session;
    protected $secret;
    protected $code;
    protected $kernel;
    
    protected $captchaPictures;
    protected $rootPictures;
    protected $routeName;
    protected $pictureStrategie;

    /**
     * Construct
     *
     * @param Session $session
     * @param string  $secret
     */
    public function __construct(KernelInterface $kernel, Session $session, $secret)
    {
        $this->kernel  = $kernel;
        $this->session = $session;
        $this->secret  = $secret;
        $this->key     = 'sfynx_form.captcha';
    }

    /**
     * set options
     *
     * @param array $options
     */    
    public function setOptions(array $options)
    {
        $defaultOptions = array( 
            'captcha_pictures' => array(
                0 => array('Sfynx', '/img/captcha/logo-sfynx.png'),
                1 => array('Pi-groupe', '/img/captcha/pigroupe.jpg')
            ),
            'root_pictures' => '@SfynxCaptchaBundle/Resources/public',
            'route_name'    => "sfynx_captcha_api_get",
            'picture_strategie' => 'security'
        ); 
        $options = array_replace($defaultOptions, $options);
        $options = array_intersect_key($options, $defaultOptions);
        
        $basePath = $this->kernel->locateResource($options['root_pictures']);
        foreach ($options as $key => $values) {
            $key = preg_replace_callback('/_([a-z])/', function($v) { return strtoupper($v[1]); }, $key);            
            if ('captchaPictures' === $key) {
                foreach ($values as $value) {
                    $path = $basePath . $value[1];
                    if (!is_file(realpath($path))) {
                        throw new FileNotFoundException($path);
                    }
                }
            }
            $this->$key = $values;
        }       
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $rand_name = $this->newCode();
        
        return $rand_name;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getPictures($is_secure = true)
    {   
        $pathPictures = $this->rootPictures;
        $routeName = $this->routeName;        
        
        if (preg_match_all("/@(?P<bundle>[a-zA-Z]+)/", $this->rootPictures, $matches))
        {
            $bundle = $matches['bundle'][0];
        } else {
            throw new FileNotFoundException($this->rootPictures);
        }
        $bundle = strtolower(str_replace('Bundle', '', $bundle));
                    
        $basePath = $this->kernel->getRootDir() . "/../web/bundles/{$bundle}";
        $arr = null;
        $path_api = null;
        $path_real_api = null;
        foreach ( $this->captchaPictures as $key => $value) {
            $arr[$key] = "/bundles/{$bundle}" . $value[1];
            $path_api[$key] = $routeName;
            $path_real_api[$key] = realpath($basePath . $value[1]);
        }
        $this->session->set($this->key.'.captchapictures', $path_real_api);
            
        if (!$is_secure && ($this->pictureStrategie != "security")) {
            return $arr;
        } 
        
        if ($is_secure && ($this->pictureStrategie == "security")) {
            return $path_api;
        }      
        
        return null;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getPicturesInSession()
    {              
        return $this->session->get($this->key.'.captchapictures');
    }     

    /**
     * Create a new code
     *
     * @return string
     */
    protected function newCode()
    {
        $captch_map = $this->captchaPictures;
        
        shuffle($captch_map);
        $this->captchaPictures = $captch_map;
        $rand_key   = array_rand($captch_map, 1);   
        $rand_name  = $captch_map[$rand_key][0];
        $rand_img   = $captch_map[$rand_key][1];

        $this->setCode($rand_key);

        return $rand_name;
    }

    /**
     * Set code
     *
     * @param string
     */
    public function setCode($code)
    {
        $this->session->set($this->key, $this->encode($code));
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        /* very important to execute phpunit test of a form using captcha */
        if ($this->kernel->getEnvironment() == "test") {
            return "1234";
        }   
        
        return $this->session->get($this->key);
    }

    /**
     * Remove code
     */
    public function removeCode()
    {
        $this->session->remove($this->key);
    }

    /**
     * Encode a new code
     *
     * @param string $code
     *
     * @return string
     */
    public function encode($code)
    {
        /* very important to execute phpunit test of a form using captcha */
        if ($this->kernel->getEnvironment() == "test") {
            return "1234";
        }   
        
        return md5($code.$this->secret);
    }
}
