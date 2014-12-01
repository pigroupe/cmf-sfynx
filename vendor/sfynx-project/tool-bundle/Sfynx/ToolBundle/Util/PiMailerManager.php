<?php
/**
 * This file is part of the <Tool> project.
 *
 * @subpackage   Tool
 * @package    Util
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2013-02-05
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\ToolBundle\Util;

use Timestampable\Fixture\SupperClassExtension;

use Sfynx\ToolBundle\Builder\PiMailerManagerBuilderInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of the Mailer manager
 *
 * <code>
 *     $Mailer    = $this-container->get('sfynx.tool.mailer_manager');
 * </code>
 * 
 * @subpackage   Tool
 * @package    Util
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiMailerManager implements PiMailerManagerBuilderInterface 
{    
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface Service container
     */
    protected $container;

    /**
     * @var \Swift_Mailer Service mailer
     */
    protected $mailer;

    /**
     * @var \Swift_Message Service message
     */
    protected $message;

    /**
     * @var array Options
     */
    protected $options = array();

    /**
     * @var array Files definition
     */
    public $files = array();
    
    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container    = $container;
        $this->mailer       = $this->container->get('mailer');
        $this->message      = \Swift_Message::newInstance();
        
        $basePath       = $this->container->getParameter("kernel.cache_dir"). '/../mailer/';
        $dir            = \Sfynx\ToolBundle\Util\PiFileManager::mkdirr($basePath);
        $this->options  = array(
            'binary'    => $dir,
            'utf8'      => true,
            'style'     => true,
            'pretty'    => true,
        );
    }
    
    /**
     * Gets the message instance.
     *
     * @return \Swift_Mime_Message
     * @access protected
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getMessage()
    {
        return $this->message;
    }    
    
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
    ) {
    	$parameters = $this->container->getParameter('sfynx.tool.mail.overloading_mail');
    	if (!empty($parameters)) {
            $to = $this->container->getParameter('sfynx.tool.mail.overloading_mail');
    	}    	
        try {
            $this->init($this->message, $from, $to, $cc, $bcc, $replayto, $subject, $body, $sender);            
            if ($is_pictureEmbed && $this->supports($this->message)) {
                $this->pictureEmbed($this->message);
            }            
            if ($is_Html2Text && $this->supports($this->message)) {
                $this->Html2Text($this->message);            
            }    
            if (is_array($filespath)) {
                foreach ($filespath as $key=>$file) {
                    $this->message->attach(\Swift_Attachment::fromPath($file));
                }
            }
            
            return $this->push($this->message);
        } catch (\Exception $e) {
            return false;
        }
    }    
    
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
    ) {
        if (is_string($cc)) {
            $cc_new    = $this->container
                    ->get('sfynx.tool.regex_manager')
                    ->verifByRegularExpression($cc, 'mail', PREG_SPLIT_NO_EMPTY);
            if ( is_array($cc_new) && (count($cc_new)==1) && (count($cc_new[0])>=1) ) {
                $cc = $cc_new[0];
            } else {
                $cc = null;
            }
        }
        if (is_string($bcc)) {
            $bcc_new = $this->container
                    ->get('sfynx.tool.regex_manager')
                    ->verifByRegularExpression($bcc, 'mail', PREG_SPLIT_NO_EMPTY);
            if ( is_array($bcc_new) && (count($bcc_new)==1) && (count($bcc_new[0])>=1) ) {
                $bcc = $bcc_new[0];
            } else {
                $cc = null;
            }
        }     
        try {
            $message
            ->setTo($to)
            ->setFrom($from)
            ->setSender($sender)
            ->setCc($cc)
            ->setBcc($bcc)
            ->setReplyTo($replayto)
            ->setSubject($subject)
            ->setBody($body,'text/html');
        } catch (\Exception $e) {
            return false;
        }
    }
        
    /**
     * Pushes mail into mail queue
     *
     * @param \Swift_Mime_Message $message E-mail message
     *
     * @access public
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function push(\Swift_Mime_Message &$message)
    {
        return $this->mailer->send($message);
    }    
    
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
    public function attach(\Swift_Mime_Message &$message, $file)
    {
        $message->attach(\Swift_Attachment::fromPath($file));
    }    
    
    /**
     * upload attached files
     *
     * @param void|array $files The files value
     *
     * @access public
     * @return array
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function uploadAttached($files = array())
    {
        $list_files = array();
        $dir = $this->container
                ->get('kernel')
                ->getRootDir(). '/../web/uploads/attachements/';
        \Sfynx\ToolBundle\Util\PiFileManager::mkdirr($dir);
        foreach ($files as $inputname => $file) {
            $list_files[$inputname] = $dir.$file['name'];
            move_uploaded_file($file['tmp_name'], $list_files[$inputname]);
            $this->files[] = $dir.$file['name'];
        }     
        
        return $list_files;
    }    
    
    /**
     * delete attached files
     *
     * @param void|array $files The files value
     *
     * @access public
     * @return array
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function deleteAttached($files = array())
    {
        try {
            foreach ($files as $inputname => $file ) {
            	unlink ($file);
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }         
    }    
    
    /**
     * Embed pictures
     *
     * @param \Swift_Mime_Message $message E-mail message
     *
     * @access public
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function pictureEmbed(\Swift_Mime_Message &$message)
    {
        $body = $message->getBody();
        $body = preg_replace_callback(
            '/(src|background)="(http[^"]*)"/',
            function ($matches) use ($message) {
                $attribute = $matches[1];
                $imagePath = $matches[2];
                if ($fp = fopen($imagePath, "r")) {
                    // You can embed files from a URL if allow_url_fopen is on in php.ini
                    $imagePath = $message->embed(\Swift_Image::fromPath($imagePath));
                    fclose($fp);
                }

                return sprintf('%s="%s"', $attribute, $imagePath);
            },
            $body
        );
        $body = preg_replace_callback(
            '/url\((http[^"]*)\)/',
            function ($matches) use ($message) {
                $imagePath = $matches[1];
                if ($fp = fopen($imagePath, "r")) {
                    // You can embed files from a URL if allow_url_fopen is on in php.ini
                    $imagePath = $message->embed(\Swift_Image::fromPath($imagePath));
                    fclose($fp);
                }

                return sprintf('url(%s)', $imagePath);
            },
            $body
        );
        $message->setBody($body, 'text/html');
    }    
    
    /**
     * Define email to text/plain
     *
     * @param \Swift_Mime_Message $message E-mail message
     *
     * @access public
     * @return void
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function Html2Text(\Swift_Mime_Message &$message)
    {
        $text = strip_tags($message->getBody());
        $message->addPart($text, 'text/plain');
    }    
    
    /**
     * Add multipart/mixed suports
     *
     * @param \Swift_Mime_Message $message E-mail message
     *
     * @access public
     * @return array
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function supports(\Swift_Mime_Message &$message)
    {
        // why multipart/mixed, because if you attach a file, it'll be the contentType
        // So be careful to not use this transformer if you don't use text/html and attach a file
        return in_array($message->getContentType(), array('multipart/mixed', 'text/html'));
    }    
    
    /**
     * Return command value
     *
     * @access protected
     * @return string
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function getCommand()
    {
        $command = $this->options['binary'];    
        if ($this->options['utf8']) {
            $command .= ' -utf8';
        }    
        if ($this->options['style']) {
            $command .= ' -style';
        }    
        if ($this->options['pretty']) {
            $command .= ' pretty';
        }    
        //@todo continue, integrate other options
    
        return $command;
    }    
        
}