<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   Admin_Utils
 * @package    Util
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle\Util;

use PiApp\AdminBundle\Builder\PiEncryptionBuilderInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of the encryption manager
 *
 * <code>
 *     $encryption    = $this-container->get('pi_app_admin.encryption_manager');
 *     
 *     <span class="hiddenLink {{ url|obfuscateLink }}">
 *
 *     <span frameborder="0" scrolling="no" width="805px" height="800px"  data-sort="3" data-hashtag="myBudget" class="hiddenLinkIframe {{ url|obfuscateLink }}" />
 *     {{ obfuscateLinkJS('iframe','hiddenLinkIframe')|raw }}
 * </code>
 * 
 * @category   Admin_Utils
 * @package    Util
 * 
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class PiEncryption implements PiEncryptionBuilderInterface 
{   

    /**
     * des encryption
     */
    const ENCRYPT_DES = 'des';
    
    /**
     * blowfish crypt encryption
     */
    const ENCRYPT_BLOWFISH_CRYPT = 'blowfish_crypt';
    
    /**
     * md5 crypt encryption
     */
    const ENCRYPT_MD5_CRYPT = 'md5_crypt';
    
    /**
     * ext crypt encryption
     */
    const ENCRYPT_EXT_CRYPT = 'ext_crypt';

    /**
     * md5 encryption
     */
    const ENCRYPT_HASH = 'hash';
    
    /**
     * md5 encryption
     */
    const ENCRYPT_MD5 = 'md5';
    
    /**
     * smd5 encryption
     */
    const ENCRYPT_SMD5 = 'smd5';

    /**
     * sha encryption
     */
    const ENCRYPT_SHA = 'sha';
    
    /**
     * ssha encryption
     */
    const ENCRYPT_SSHA = 'ssha';
    
    /**
     * lmpassword encryption
     */
    const ENCRYPT_LMPASSWORD = 'lmpassword';
    
    /**
     * ntpassword encryption
     */
    const ENCRYPT_NTPASSWORD = 'ntpassword';
    
    /**
     * no encryption
     */
    const ENCRYPT_PLAIN = 'plain';
    
    const ENCRYPT_MAIL_UNSUBSCRIBE = 'mailunsubscribe';
    
    /**
     * returns all supported password encryptions types
     *
     * @return array
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public static function getSupportedEncryptionTypes()
    {
        return array(
            self::ENCRYPT_BLOWFISH_CRYPT,
            self::ENCRYPT_EXT_CRYPT,
            self::ENCRYPT_DES,
            self::ENCRYPT_HASH,
            self::ENCRYPT_MD5,
            self::ENCRYPT_MD5_CRYPT,
            self::ENCRYPT_PLAIN,
            self::ENCRYPT_SHA,
            self::ENCRYPT_SMD5,
            self::ENCRYPT_SSHA,
            self::ENCRYPT_LMPASSWORD,
            self::ENCRYPT_NTPASSWORD,
            self::ENCRYPT_MAIL_UNSUBSCRIBE
        );
    }
    
    /**
     * encryptes password
     *
     * @param string $_password
     * @param string $_method
     * @return string the password
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public static function encryptPassword($_password, $_method)
    {
        switch (strtolower($_method)) {
            case self::ENCRYPT_BLOWFISH_CRYPT:
                $salt = '$2$' . self::getRandomString(13);
                $password = '{CRYPT}' . crypt($_password, $salt);
                break;
                
            case self::ENCRYPT_EXT_CRYPT:
                $salt = self::getRandomString(9);
                $password = '{CRYPT}' . crypt($_password, $salt);
                break;
                
            case self::ENCRYPT_MD5:
                $password = '{MD5}' . base64_encode(pack("H*", md5($_password)));
                break;
                
            case self::ENCRYPT_MD5_CRYPT:
                $salt = '$1$' . self::getRandomString(9);
                $password = '{CRYPT}' . crypt($_password, $salt);
                break;
                
            case self::ENCRYPT_PLAIN:
                $password = $_password;
                break;
                
            case self::ENCRYPT_SHA:
                if(function_exists('mhash')) {
                    $password = '{SHA}' . base64_encode(mhash(MHASH_SHA1, $_password));
                }
                break;

            case self::ENCRYPT_HASH:
                if(function_exists('hash')) {
                    $password = hash_hmac('ripemd160', $_password, '314secret911');
                }
                break;
                
            case self::ENCRYPT_SMD5:
                if(function_exists('mhash')) {
                    $salt = self::getRandomString(8);
                    $hash = mhash(MHASH_MD5, $_password . $salt);
                    $password = '{SMD5}' . base64_encode($hash . $salt);
                }
                break;
                
            case self::ENCRYPT_SSHA:
                if(function_exists('mhash')) {
                    $salt = self::getRandomString(8);
                    $hash = mhash(MHASH_SHA1, $_password . $salt);
                    $password = '{SSHA}' . base64_encode($hash . $salt);
                }
                break;
                
            case self::ENCRYPT_LMPASSWORD:
                $crypt = new PEAR_Crypt_CHAP_MSv1();
                $password = strtoupper(bin2hex($crypt->lmPasswordHash($_password)));
                break;
                
            case self::ENCRYPT_NTPASSWORD:
                $crypt = new PEAR_Crypt_CHAP_MSv1();
                $password = strtoupper(bin2hex($crypt->ntPasswordHash($_password)));
                
                // @todo replace Crypt_CHAP_MSv1
                //$password = hash('md4', Zend_Auth_Adapter_Http_Ntlm::toUTF16LE($_password), TRUE);
                break;
                
            case self::ENCRYPT_MAIL_UNSUBSCRIBE:
                $crypt 				= new PEAR_Crypt_CHAP_MSv1();
				$crypt->password 	= $_password;
				$crypt->challenge 	= pack('H*', '102DB5DF085D3041');
				
//				$unipw 					= '{MAIL}' . $crypt->str2unicode($crypt->password);    
//              $password['unicode-pw']	= '{MAIL}' . strtoupper(bin2hex($unipw));
//              $password['NT-HASH']	= '{MAIL}' . strtoupper(bin2hex($crypt->ntPasswordHash()));
//              $password['NT-Resp']	= '{MAIL}' . strtoupper(bin2hex($crypt->challengeResponse()));
//              $password['LM-HASH']	= '{MAIL}' . strtoupper(bin2hex($crypt->lmPasswordHash()));
//              $password['LM-Resp']	= '{MAIL}' . strtoupper(bin2hex($crypt->lmChallengeResponse()));
//              $password['Response']	= '{MAIL}' . strtoupper(bin2hex($crypt->response()));
                
                $password	=	strtoupper(bin2hex($crypt->ntPasswordHash()));
                break;                

            case self::ENCRYPT_DES:
                $salt = self::getRandomString(2);
                $password  = '{CRYPT}'. crypt($_password, $salt);
                break;
                                
            default:
				break;
        }
        
        if (! $password) {
            throw new Zend_Exception("$_method is not supported by your php version");
        }
        
        return $password;
    }    
    
    /**
     * generates a randomstrings of given length
     *
     * @param int $_length
     * @return string the random value
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public static function getRandomString($_length)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        
        $randomString = '';;
        for ($i=0; $i<(int)$_length; $i++) {
            $randomString .= $chars[mt_rand(1, strlen($chars)) -1];
        }
        
        return $randomString;
    }
    
    /**
     * encrypt string
     *
     * @param string $string
     * @param string $key
     * @return string the encrypt string value
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */ 
    public static function encryptFilter($string, $key = "0A1TG4GO")
    {
    	$key = $key . "0A1TG4GO";
    	$result = '';
    	for($i=0; $i<strlen($string); $i++) {
    		$char = substr($string, $i, 1);
    		$keychar = substr($key, ($i % strlen($key))-1, 1);
    		$char = chr(ord($char)+ord($keychar));
    		$result.=$char;
    	}
    	return strtr(base64_encode($result), '+/=', '-_,');
    }
    
    /**
     * decrypt string
     *
     * @param string $string
     * @param string $key
     * @return string the decrypt string value
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public static function decryptFilter($string, $key = "0A1TG4GO")
    {
    	$key = $key . "0A1TG4GO";
    	$result = '';
    	$string = base64_decode(strtr($string, '-_,', '+/='));
    	for($i=0; $i<strlen($string); $i++) {
    		$char = substr($string, $i, 1);
    		$keychar = substr($key, ($i % strlen($key))-1, 1);
    		$char = chr(ord($char)-ord($keychar));
    		$result.=$char;
    	}
    	return $result;
    }
    
    /**
     * Obfuscate link. SEO worst practice.
     *
     * @param string $url
     * @return string the encrypt obfuscate Link value
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public static function obfuscateLinkEncrypt($url, $_base16 = "0A12B34C56D78E9F")
    {
    	$output = "";
    	for ($i = 0; $i < strlen($url); $i++) {
    		$cc = ord($url[$i]);
    		$ch = $cc >> 4;
    		$cl = $cc - ($ch * 16);
    		$output .= $_base16[$ch] . $_base16[$cl];
    	}
    	return $output;
    }
    
    /**
     * Obfuscate link JS. SEO worst practice.
     *
     * @param string $fileName
     * @return string the decrypt obfuscate Link JS code.
     * @access public
     * 
     * @access public
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public static function obfuscateLinkDecrypt($balise = "a", $class = "hiddenLink", $base16 = "0A12B34C56D78E9F")
    {
    	// We open the buffer.
    	ob_start ();
    	?>
                    <script type="text/javascript">
                    //<![CDATA[
                        jQuery(document).ready(function() {  
                            var listitems = jQuery('.<?php echo $class; ?>').get();
                            var index_ = 0;
                            listitems.sort(function(a, b) {
                                var hashtag = parsedURL.anchor ? '#'+parsedURL.anchor : null ;       //FORMAT HAS TO MATCH #AAAAA
                                var hashtag_ = '#' + $(a).data('hashtag');
                                var sort_a = parseInt($(a).data('sort'));
                                var sort_b = parseInt($(b).data('sort'));
                                if (hashtag == hashtag_) {
                                    return 0;
                                } else if ( (sort_a != undefined) && (sort_b != undefined) && (sort_a < sort_b) ) {
                                    return sort_a;
                                } else if ( (sort_a != undefined) && (sort_b != undefined) && (sort_a > sort_b) ) {
                                    return sort_a;
                                } else {
                                    index_ = index_ +1;
                                    return index_;
                                }
                            })
                            $.each(listitems, function(index, span) { 
                                    $(span).removeClass('<?php echo $class; ?>');
            
                                    var base16  = "<?php echo $base16; ?>";
                                    var link    = document.createElement('<?php echo $balise; ?>');
                                    var styles  = span.className.split(' ');
                                    var encoded = styles[0];
                                    var decoded = '';        
                                    for (var i = 0; i < encoded.length; i += 2) {
                                        var ch = base16.indexOf(encoded.charAt(i));
                                        var cl = base16.indexOf(encoded.charAt(i+1));
                                        decoded += String.fromCharCode((ch*16)+cl);
                                    }        
                                    styles.shift();
                                    link.className  = styles.join(' ');
                                    <?php if (in_array($balise, array('img', 'iframe'))) : ?>
                                    link.src       = decoded;
                                    <?php else : ?>
                                    link.href       = decoded;
                                    <?php endif; ?>
                                    
                                    var attributes = $(span).prop("attributes");
                                    $.each(attributes, function() {
                                        link.setAttribute(this.name, this.value); 
                                    });

                                    link.innerHTML  = span.innerHTML;
            
                                    $(span).replaceWith(link);                                
                            });
                        });
                    //]]>
                    </script>
                    
        <?php 
        // We retrieve the contents of the buffer.
        $_content = ob_get_contents ();
        // We clean the buffer.
        ob_clean ();
        // We close the buffer.
        ob_end_flush ();
                
        return $_content;                                
    }     

}
