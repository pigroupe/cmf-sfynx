<?php
/**
 * This file is part of the <Auth> project.
 *
 * @category   Auth
 * @package    User
 * @subpackage Submit
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
namespace Sfynx\AuthBundle\Validator;

use Symfony\Component\HttpFoundation\Request;
use Sfynx\ToolBundle\Util\PiEncryption;

/**
 * @category   Auth
 * @package    User
 * @subpackage Submit
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class SubmitUserValidator
{
    private $submitedUserDatas = null ;
    private $validationCode = null;
    private $validationErrors = null;
    private $validateLocation = null;

    public function setSubmitedUserDatas(Request $request)
    {
        $datas = $request->getContent();
        if (!$datas || trim($datas) == "") {
            throw new \Exception(
                json_encode(array("error" => "You must send datas")),
                400
            );
        }
        if (!$this->isJson($datas)) {
            throw new \Exception(
                json_encode(array("error" => "Sended datas must be formated in Json")),
                415
            );
        }
        $this->submitedUserDatas = json_decode($datas, true);
        //
        $key    = $request->headers->get('x-auth-ws_key', '');
        if (!empty($key)) {
            $this->submitedUserDatas['connexion']['password'] = PiEncryption::decryptFilter(
                $this->submitedUserDatas['connexion']['password'], 
                $key
            );
        }

        return $this->submitedUserDatas;
    }
    

    public function isValide()
    {
        if ($this->submitedUserDatas === null) {
             throw new \Exception("You must set Submited User Datas before launch isValid function");
        }
        $this->valideAll();

        return ($this->validationCode === null) ? true : false;
    }

    public function getValidationCode()
    {
        return $this->validationCode;
    }

    public function getErrors()
    {
        return $this->validationErrors ? json_encode($this->validationErrors) : null;
    }
    
    private function isJson($string)
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }    

    private function valideAll()
    {
        $this->validateFirstName();
        $this->validateLastName();
        $this->validateEmail();
        $this->validateRole();
        $this->validateUserName();
        $this->validatePassword();
        $this->validateLocation();
        
//        print_r($this->getErrors());
//        print_r($this->getValidationCode());
//        print_r($this->submitedUserDatas);
//        exit;
    }

    private function setValidationCode($code)
    {
        if ($this->validationCode === null) {
            $this->validationCode = 400;
        }
    }

    private function setValidationErrors($errorType, $errorMessage)
    {
        if ($this->validationErrors === null) {
            $this->validationErrors = array();
        }
        if (!isset($this->validationErrors[$errorType])) {
            $this->validationErrors[$errorType] = $errorMessage;
        }
    }

    private function validateFirstName()
    {
        if (!isset($this->submitedUserDatas['first_name']) || trim($this->submitedUserDatas['first_name']) === '') {
            $this->setValidationCode(400);
            $this->setValidationErrors('first_name', 'Le prénom est obligatoire.');

            return false;
        }
        if (strlen($this->submitedUserDatas['first_name']) > 50 || strlen($this->submitedUserDatas['first_name']) < 2) {
            $this->setValidationCode(400);
            $this->setValidationErrors('first_name', 'Le prénom doit avoir entre 2 et 50 caractéres.');

            return false;
        }
        // \p{L} matches a single code point in the category "letter".
        // \p{N} matches any kind of numeric character in any script.
        // old regex =>     /^[\p{L} ,\'-]+$/
        // new regex =>     /^[[:alpha:]\s'"\-_&@!?()\[\]-]*$/u
        if (!preg_match("/^[[:alpha:]\s'\x22\-_&@!?()\[\]-]*$/u", $this->submitedUserDatas['first_name'])) {
            $this->setValidationCode(400);
            $this->setValidationErrors('first_name', "Le prénom n'est pas valide.");

            return false;
        }
    }

    private function validateLastName()
    {
        if (!isset($this->submitedUserDatas['last_name']) || trim($this->submitedUserDatas['last_name']) === '') {
            $this->setValidationCode(400);
            $this->setValidationErrors('last_name', 'Le nom est obligatoire.');

            return false;
        }
        if (strlen($this->submitedUserDatas['last_name']) > 50 || strlen($this->submitedUserDatas['last_name']) < 2) {
            $this->setValidationCode(400);
            $this->setValidationErrors('last_name', 'Le nom doit avoir entre 2 et 50 caractéres.');

            return false;
        }
        if (!preg_match("/^[[:alpha:]\s'\x22\-_&@!?()\[\]-]*$/u", $this->submitedUserDatas['last_name'])) {
            $this->setValidationCode(400);
            $this->setValidationErrors('last_name', "Le nom n'est pas valide.");

            return false;
        }
    }

    private function validateEmail()
    {
        if (!isset($this->submitedUserDatas['email']) || trim($this->submitedUserDatas['email']) === '') {
            $this->setValidationCode(400);
            $this->setValidationErrors('email', "L'adresse e-mail est obligatoire.");

            return false;
        }
        if (strlen($this->submitedUserDatas['email']) > 50) {
            $this->setValidationCode(400);
            $this->setValidationErrors('email', "L'adresse e-mail ne doit pas depasser 50 caractéres.");

            return false;
        }
        if (!preg_match("/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+\.[a-zA-Z]{2,4}/", $this->submitedUserDatas['email'])) {
            $this->setValidationCode(400);
            $this->setValidationErrors('email', "L'adresse e-mail est invalide.");

            return false;
        }
    }
    
    private function validateUserName()
    {
        if (!isset($this->submitedUserDatas['connexion']['username']) || trim($this->submitedUserDatas['connexion']['username']) === '') {
            $this->setValidationCode(400);
            $this->setValidationErrors('login', 'Le login est obligatoire.');

            return false;
        }
        if (strlen($this->submitedUserDatas['connexion']['username']) > 50 || strlen($this->submitedUserDatas['connexion']['username']) < 8) {
            $this->setValidationCode(400);
            $this->setValidationErrors('login', 'Le login doit avoir entre 8 et 50 caractéres.');

            return false;
        }
    }   
    
    private function validateRole()
    {
        if (!isset($this->submitedUserDatas['connexion']['role']) || trim($this->submitedUserDatas['connexion']['role']) === '') {
            $this->setValidationCode(400);
            $this->setValidationErrors('role', 'Le rôle est obligatoire.');

            return false;
        }
        if (ctype_upper($this->submitedUserDatas['connexion']['role'])) {
            $this->setValidationCode(400);
            $this->setValidationErrors('role', 'Le rôle doit être en majuscule');

            return false;
        }
        if (substr($this->submitedUserDatas['connexion']['role'], 0, 5) != "ROLE_") {
            $this->setValidationCode(400);
            $this->setValidationErrors('role', 'Le rôle doit commencer par "ROLE_"');

            return false;
        }
    }    

    private function validatePassword()
    {
        if (!isset($this->submitedUserDatas['connexion']['password']) || trim($this->submitedUserDatas['connexion']['password']) === '') {
            $this->setValidationCode(400);
            $this->setValidationErrors('password', 'Le mot de passe est obligatoire.');

            return false;
        }
        if (strlen($this->submitedUserDatas['connexion']['password']) > 50 || strlen($this->submitedUserDatas['connexion']['password']) < 8) {
            $this->setValidationCode(400);
            $this->setValidationErrors('password', 'Le mot de passe doit avoir entre 8 et 50 caractéres.');

            return false;
        }
    }

    private function validateLocation()
    {
        if (isset($this->submitedUserDatas['location'])) {
            $this->validateAdress();
            $this->validatePostalCode();
            $this->validateCity();
            if ($this->validateLocation !== null) {
                $this->setValidationCode(400);
                $this->setValidationErrors('location', $this->validateLocation);
            }
        }
    }

    private function validateAdress()
    {
        if (!isset($this->submitedUserDatas['location']['address']) || trim($this->submitedUserDatas['location']['address']) === '') {
            if ($this->validateLocation === null) {
                $this->validateLocation = array();
            }
            $this->validateLocation['address'] = "Si location est present, l'address doit l'être aussi";

            return false;
        }
        if (strlen($this->submitedUserDatas['location']['address']) > 100|| strlen($this->submitedUserDatas['location']['address']) < 4) {
            if ($this->validateLocation === null) {
                $this->validateLocation = array();
            }
            $this->validateLocation['address'] = "L'adresse ne doit pas depasser 100 caractéres.";

            return false;
        }
    }

    private function validatePostalCode()
    {
        if (!isset($this->submitedUserDatas['location']['cp']) || trim($this->submitedUserDatas['location']['cp']) === '') {
            if ($this->validateLocation === null) {
                $this->validateLocation = array();
            }
            $this->validateLocation['cp'] = "Si location est present, le code postal doit l'être aussi";

            return false;
        }
        if (!preg_match("/^[0-9]{5}$/", $this->submitedUserDatas['location']['cp'])) {
            if ($this->validateLocation === null) {
                $this->validateLocation = array();
            }
            $this->validateLocation['cp'] = "Le code postal ". $this->submitedUserDatas['location']['cp'] . " n'est pas valide.";

            return false;
        }
    }

    private function validateCity()
    {
        if (!isset($this->submitedUserDatas['location']['city']) || trim($this->submitedUserDatas['location']['city']) === '') {
            if ($this->validateLocation === null) {
                $this->validateLocation = array();
            }
            $this->validateLocation['city'] = "Si location est present, la ville doit l'être aussi";

            return false;
        }
        if (strlen($this->submitedUserDatas['location']['city']) > 50 || strlen($this->submitedUserDatas['location']['city']) < 2) {
            if ($this->validateLocation === null) {
                $this->validateLocation = array();
            }
            $this->validateLocation['city'] = "La ville doit avoir entre 2 et 50 caractéres.";

            return false;
        }
    }
}
