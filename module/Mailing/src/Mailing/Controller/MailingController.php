<?php

namespace Mailing\Controller;

use Mailing\Model\Db\MailType;
use Mailing\Service\MailType\MailTypeServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Mailing\Model\Db\Mail;
use Mailing\Service\Mailing\MailingServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MailingController extends AbstractActionController {
    use MailingServiceAwareTrait;
    use MailTypeServiceAwareTrait;
    use UserServiceAwareTrait;

    public function indexAction()
    {
        /**
         * @var Mail[] $mails
         * @var MailType[] $types
         */
        $mails = $this->getMailingService()->getMails();
        $types = $this->getMailTypeService()->getMailsTypes();

        return new ViewModel([
            'mails' => $mails,
            'types' => $types,
        ]);
    }

    public function mailTestAction()
    {
        $mail = $this->getUserService()->getConnectedUser()->getEmail();
        $this->getMailingService()->sendTestMail($mail);
        return $this->redirect()->toRoute('mailing');
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

        return $this->redirect()->toRoute('mailing');
    }

    public function reEnvoiAction()
    {
        $retour = $this->params()->fromQuery('retour');

        $mailId = $this->params()->fromRoute('id');
        $mail = $this->getMailingService()->getMail($mailId);

        $newMail = $this->getMailingService()->reEnvoi($mail);
        $this->getMailingService()->addAttachement($newMail, $mail->getAttachementType(), $mail->getAttachementId());

        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('mailing');
    }
}