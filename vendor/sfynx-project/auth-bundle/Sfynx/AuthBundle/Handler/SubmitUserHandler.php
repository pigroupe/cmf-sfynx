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
namespace Sfynx\AuthBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;
use FOS\UserBundle\Model\UserManager;
use Sfynx\AuthBundle\Entity\User;
use Sfynx\AuthBundle\Validator\SubmitUserValidator;
use Sfynx\AuthBundle\Model\UserWS;

/**
 * This class is used to process json data transmit in post from the new api Webservice
 * If data are valide, a new User is created
 * If data are not valide, the handler return errors in json
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
 */
class SubmitUserHandler
{
    /** @var SubmitUserValidator */
    private $validator;
    
    /** @var Validator */
    private $sfValidator;
    
    /** @var UserManager */
    private $userManager;
    
    /** @var MailerInterface */
    private $mailer;
    
    /** @var User */
    private $newUser = null;
    
    /** @var array */
    private $submitDatas = null;    

    public function __construct(SubmitUserValidator $validator, UserManager $userManager, Validator $sfValidator, $mailer = null)
    {
        $this->validator   = $validator;
        $this->sfValidator = $sfValidator;
        $this->userManager = $userManager;
        $this->mailer      = $mailer;
    }

    public function bindDatas(Request $request)
    {
        $this->submitDatas = $this->validator->setSubmitedUserDatas($request);
    }

    public function process()
    {
        if ($this->submitDatas === null) {
             throw new \Exception("You must bind datas before process");
        }
        if (!$this->validator->isValide()) {
            throw new \Exception(
                $this->validator->getErrors(),
                $this->validator->getValidationCode()
            );
        }
        $this->createUser();
        if (!$this->newUser || !$this->newUser->getId()) {
            throw new \Exception(
                json_encode(array("error"  => "Erreur serveur, veuillez réessayer ultérieurement.")),
                500
            );
        }

        return $this->getUserInJson();
    }

    private function createUser()
    {
        $this->testIfEmailAlreadyInUse();
        $this->testIfUsernameAlreadyInUse();
        $this->populateUserWithSubmitedDatas();
        $modelErrors = $this->sfValidator->validate($this->newUser);
        if (count($modelErrors)) {
            throw new \Exception(
                json_encode($modelErrors),
                400
            );
        }
        //
        $this->userManager->updateUser($this->newUser);
        if ($this->mailer) {
            $this->mailer->sendConfirmationEmailMessage($this->newUser);
        }
    }

    private function testIfEmailAlreadyInUse()
    {
        $email = $this->userManager->findUserByEmail($this->submitDatas['email']);
        if ($email) {
            throw new \Exception(
                json_encode(array("email" => "Cet email est déjà utilisé.")),
                403
            );
        }
    }
    
    private function testIfUsernameAlreadyInUse()
    {
        $email = $this->userManager->findUserByUsername($this->submitDatas['email']);
        if ($email) {
            throw new \Exception(
                json_encode(array("email" => "Cet email est déjà utilisé.")),
                403
            );
        }
    }    

    private function populateUserWithSubmitedDatas()
    {
        $this->newUser = new User();
        if (isset($this->submitDatas['enabled'])) {
            $this->newUser->setEnabled($this->submitDatas['enabled']);
        }
        $this->newUser->setNickname($this->submitDatas['first_name']);
        $this->newUser->setName($this->submitDatas['last_name']);
        $this->newUser->setEmail($this->submitDatas['email']);
        //
        $this->newUser->addRole($this->submitDatas['connexion']['role']);
        $this->newUser->setUsername($this->submitDatas['connexion']['username']);
        $this->newUser->setPlainPassword($this->submitDatas['connexion']['password']);
        if (isset($this->submitDatas['location'])) {
            $this->newUser->setAddress($this->submitDatas['location']['address']);
            $this->newUser->setZipCode($this->submitDatas['location']['cp']);
            $this->newUser->setCity($this->submitDatas['location']['city']);
            if (isset($this->submitDatas['location']['country'])) {
                $this->newUser->setCountry($this->submitDatas['location']['country']);
            }
        }
        if (isset($this->submitDatas['birthday'])) {
            $this->newUser->setBirthday($this->submitDatas['birthday']);
        }
        if (isset($this->submitDatas['global_optin'])) {
            $this->newUser->setGlobalOptIn($this->submitDatas['global_optin']);
        }
        if (isset($this->submitDatas['site_optin'])) {
            $this->newUser->setSiteOptIn($this->submitDatas['site_optin']);
        }
    }

    private function getUserInJson()
    {
        $userWs = new UserWS($this->newUser);

        return $userWs->jsonSerialize();
    }
}
