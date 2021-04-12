<?php

namespace Mailing\Controller;

use Mailing\Model\Db\MailType;
use Mailing\Service\MailType\MailTypeServiceAwareTrait;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Mailing\Model\Db\Mail;
use Mailing\Service\Mailing\MailingServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class MailingController extends AbstractActionController {
    use MailingServiceAwareTrait;
    use MailTypeServiceAwareTrait;
    use EtatServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use UserServiceAwareTrait;

    public function indexAction()
    {
        $fromQueries  = $this->params()->fromQuery();
        $adresse      = (trim($fromQueries['adresse']['label']) != '')?trim($fromQueries['adresse']['label']):null;
        $etatId       = $fromQueries['etat'];
        $typeId       = $fromQueries['type'];

        $params = ['adresse' => $adresse, 'etat' => $etatId, 'type' => $typeId];

        /**
         * @var Mail[] $mails
         * @var MailType[] $types
         */
        $mails = $this->getMailingService()->getMailsWithFiltre($params);
        $types = $this->getMailTypeService()->getMailsTypes();
        $type = $this->getEtatTypeService()->getEtatTypeByCode('MAIL');
        $etats = $this->getEtatService()->getEtatsByType($type);

        return new ViewModel([
            'mails' => $mails,
            'types' => $types,
            'etats' => $etats,
            'params' => $params,
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

    public function rechercherAdresseAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $adresses = $this->getMailingService()->findAdressetByTerm($term);
            $result = $this->getMailingService()->formatAdresseJSON($adresses);
            return new JsonModel($result);
        }
        exit;
    }
}