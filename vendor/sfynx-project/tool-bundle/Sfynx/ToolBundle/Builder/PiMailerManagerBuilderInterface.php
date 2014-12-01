<?php
/**
 * This Locale is part of the <Admin> project.
 *
 * @subpackage   Tool
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2013-02-05
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Builder;

/**
 * PiMailerManagerBuilderInterface interface.
 *
 * @subpackage   Tool
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface PiMailerManagerBuilderInterface
{
    /**
     * Gets the message instance.
     *
     * @return \Swift_Mime_Message
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getMessage();
    
    /**
     * Instantiates the mailer
     *
     * @param string  $from            The from value
     * @param mixed   $to              The to value
     * @param string  $subject         The subject value
     * @param string  $body            The body value
     * @param mixed   $cc              The cc value
     * @param mixed   $bcc             The bcc value
     * @param string  $replayto        The replayto value
     * @param array   $filespath       The filespath value
     * @param boolean $is_pictureEmbed The is_pictureEmbed value
     * @param boolean $is_Html2Text    THe is_Html2Text value
     * @param mixed   $sender          The sender value
     *
     * @access public
     * @return false|true
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */  
    public function send(
        $from,
        $to,
        $subject,
        $body,
        $cc = null,
        $bcc = null,
        $replayto = null,
        $filespath = null,
        $is_pictureEmbed = false,
        $is_Html2Text = false,
        $sender = null            
    );
    
    
    /**
     * init the mailer into mail queue
     *
     * @param \Swift_Mime_Message $message  E-mail message
     * @param string              $from     The from value
     * @param mixed               $to       The to value
     * @param mixed               $cc       The cc value
     * @param mixed               $bcc      The bcc value
     * @param string              $replayto The replayto value
     * @param string              $subject  The subject value
     * @param array               $body     The body value
     * @param mixed               $sender   The sender value
     *
     * @access public
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function init(
        \Swift_Mime_Message &$message,
        $from,
        $to,
        $cc,
        $bcc,
        $replayto,
        $subject,
        $body,
        $sender
    );
    
    /**
     * Pushes mail into mail queue
     *
     * @param \Swift_Mime_Message $message E-mail message
     *
     * @access public
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function push(\Swift_Mime_Message &$message);
    
    
    /**
     * attach file
     *
     * @param \Swift_Mime_Message $message E-mail message
     * @param string              $file    The file value
     *
     * @access public
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function attach(\Swift_Mime_Message &$message, $file);
    
    /**
     * upload attached files
     *
     * @param void|array $files The files value
     *
     * @access public
     * @return array
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function uploadAttached($files = array());
    
    
    /**
     * delete attached files
     *
     * @param void|array $files The files value
     *
     * @access public
     * @return array
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteAttached($files = array());
    
    /**
     * Embed pictures
     *
     * @param \Swift_Mime_Message $message E-mail message
     *
     * @access public
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function pictureEmbed(\Swift_Mime_Message &$message);
    
    /**
     * Define email to text/plain
     *
     * @param \Swift_Mime_Message $message E-mail message
     *
     * @access public
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function Html2Text(\Swift_Mime_Message &$message);    
}