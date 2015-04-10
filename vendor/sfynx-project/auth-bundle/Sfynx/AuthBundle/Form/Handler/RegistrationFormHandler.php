<?php
namespace Sfynx\AuthBundle\Form\Handler;

use Sfynx\CoreBundle\Form\Handler\AbstractFormHandler;
use FOS\UserBundle\Model\UserInterface;

class RegistrationFormHandler extends AbstractFormHandler
{
    protected $user;
    protected $confirmation;
    
    public function __construct(FormInterface $form, Request $request, $userManager, $mailer)
    {
        parent::__construct($form, $request);
        
        $this->mailer = $mailer;
        $this->userManager = $userManager;
    }
    
    public function setUser(UserInterface $user) 
    {
        $this->user = $user;
    }
    
    public function setConfirmation($confirmation) 
    {
        $this->confirmation = $confirmation;
    }
    
    protected function getValidMethods()
    {
        return array('POST');
    }
    
    protected function onSuccess()
    {
        if ($this->confirmation) {
            $user->setEnabled(false);
            $this->mailer->sendConfirmationEmailMessage($this->user);
        } else {
            $user->setConfirmationToken(null);
            $user->setEnabled(true);
        }
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPermissions(array('VIEW', 'EDIT', 'CREATE', 'DELETE'));

        $this->userManager->updateUser($this->user, true);
    }   
}
