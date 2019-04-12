<?php

namespace Mailing\Controller\Mailing;

use Utilisateur\Service\User\UserServiceAwareTrait;
use Mailing\Model\Db\Mail;
use Mailing\Service\Mailing\MailingServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MailingController extends AbstractActionController {
    use MailingServiceAwareTrait;
    use UserServiceAwareTrait;

    public function indexAction()
    {
        /** @var Mail[] $mails */
        $mails = $this->getMailingService()->getMails();

        return new ViewModel([
            'mails' => $mails,
        ]);
    }

    public function mailTestAction()
    {
        $mail = $this->getUserService()->getConnectedUser()->getEmail();
        $this->getMailingService()->sendTestMail($mail);
        $this->redirect()->toRoute('mailing');
    }

    public function afficherAction()
    {
        $mailId = $this->params()->fromRoute('id');
        $mail = $this->getMailingService()->getMail($mailId);

        return new ViewModel([
            'title' => 'Affichage du mail',
            'mail' => $mail,
        ]);
    }

    public function effacerAction()
    {
        $mailId = $this->params()->fromRoute('id');
        $mail = $this->getMailingService()->getMail($mailId);

        $this->getMailingService()->delete($mail);

        $this->redirect()->toRoute('mailing');
    }

    public function reEnvoiAction()
    {
        $mailId = $this->params()->fromRoute('id');
        $mail = $this->getMailingService()->getMail($mailId);

        $this->getMailingService()->reEnvoi($mail);

        $this->redirect()->toRoute('mailing');
    }
}