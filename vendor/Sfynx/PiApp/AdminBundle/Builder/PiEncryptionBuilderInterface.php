<?php
/**
 * This file is part of the <Admin> project.
 *
 * @category   Admin_Builders
 * @package    Builder
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-01-18
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PiApp\AdminBundle\Builder;

/**
 * PiArrayManagerBuilderInterface interface.
 *
 * @category   Admin_Builders
 * @package    Builder
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
interface PiEncryptionBuilderInterface
{
	public static function getSupportedEncryptionTypes();
	public static function encryptPassword($_password, $_method);
	public static function getRandomString($_length);
	
	public static function encryptFilter($string, $key = "0A1TG4GO");
	public static function decryptFilter($string, $key = "0A1TG4GO");
	
	public static function obfuscateLinkEncrypt($url, $_base16 = "0A12B34C56D78E9F");
	public static function obfuscateLinkDecrypt($balise = "a", $class = "hiddenLink", $base16 = "0A12B34C56D78E9F");
}