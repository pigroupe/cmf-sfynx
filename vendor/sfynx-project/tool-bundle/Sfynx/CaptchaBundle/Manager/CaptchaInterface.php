<?php

/*
 * This file is part of the Genemu package.
 *
 * (c) Etienne de Longeaux <etienne.delongeaux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sfynx\CaptchaBundle\Manager;

/**
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface CaptchaInterface
{    
    /**
     * set options
     *
     * @param array $options
     */    
    public function setOptions(array $options);
    
    /**
     * Get name of the selected picture
     *
     * @return string
     */
    public function getName();
    
    /**
     * Get name of all pictures
     * 
     * @param boolean $is_secure
     * 
     * @return string
     */    
    public function getPictures($is_secure = true);
}
