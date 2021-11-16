<?php

namespace EntretienProfessionnel\Service\Notification;

use Application\Service\Agent\AgentServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Service\Url\UrlServiceAwareTrait;
use UnicaenMail\Entity\Db\Mail;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

class NotificationService {
    use AgentServiceAwareTrait;
    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use UrlServiceAwareTrait;

    /** Notifications liées à une campagne d'entretiens professionnels ************************************************/

    public function triggerCampagneOuvertureDirections(Campagne $campagne) : Mail
    {
        $vars = ['campagne' => $campagne];

        $mail_DAC = $this->parametreService->getParametreByCode('GLOBAL','MAIL_LISTE_DAC')->getValeur();
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode('CAMPAGNE_OUVERTURE_DAC', $vars);
        $mailDac = $this->getMailService()->sendMail($mail_DAC, $rendu->getSujet(), $rendu->getCorps());
        $mailDac->setMotsClefs([$campagne->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mailDac);

        return $mailDac;
    }

    public function triggerCampagneOuverturePersonnels(Campagne $campagne)
    {
        $vars = ['campagne' => $campagne];

        $mail_BIATS = $this->parametreService->getParametreByCode('GLOBAL','MAIL_LISTE_BIATS')->getValeur();
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode('CAMPAGNE_OUVERTURE_BIATSS', $vars);
        $mailBiats = $this->getMailService()->sendMail($mail_BIATS, $rendu->getSujet(), $rendu->getCorps());
        $mailBiats->setMotsClefs([$campagne->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mailBiats);

        return $mailBiats;
    }

    /** Notifications liées à la convation à un entretien *************************************************************/

    public function triggerConvocationDemande(EntretienProfessionnel $entretien) : Mail
    {
        $vars = ['entretien' => $entretien, 'campagne' => $entretien->getCampagne()];
        $url = $this->getUrlService();
        $url->setVariables($vars);
        $vars['UrlService'] = $url;

        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("ENTRETIEN_CONVOCATION_ENVOI", $vars);
        $mail = $this->getMailService()->sendMail($entretien->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerConvocationAcceptation(EntretienProfessionnel $entretien) : Mail
    {
        $vars = ['entretien' => $entretien, 'campagne' => $entretien->getCampagne(), 'agent' => $entretien->getAgent()];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("ENTRETIEN_CONVOCATION_ACCEPTER", $vars);
        $mail = $this->getMailService()->sendMail($entretien->getResponsable()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    /** Notification liées aux validations de l'entretien professionnel d'un agent ************************************/

    public function triggerValidationResponsableEntretien(EntretienProfessionnel $entretien) : Mail
    {
        $vars = ['agent' => $entretien->getAgent(), 'campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("ENTRETIEN_VALIDATION_1-RESPONSABLE", $vars);
        $mail = $this->getMailService()->sendMail($entretien->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerValidationAgent(EntretienProfessionnel $entretien) : Mail
    {
        $responsables = $this->getAgentService()->getResponsablesHierarchiques($entretien->getAgent());
        $mailResponsables = "";
        foreach ($responsables as $responsable) {
            if ($mailResponsables !== "" AND $responsable->getEmail() !== null) $mailResponsables .= ",";
            if ($responsable->getEmail() !== null) $mailResponsables .= $responsable->getEmail();
        }

        $vars = ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent(), 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("ENTRETIEN_VALIDATION_2-AGENT", $vars);
        $mail = $this->getMailService()->sendMail($mailResponsables, $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerValidationResponsableHierarchique(EntretienProfessionnel $entretien) : Mail
    {
        $vars = ['agent' => $entretien->getAgent(), 'campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("ENTRETIEN_VALIDATION_3-HIERARCHIE", $vars);
        $mail = $this->getMailService()->sendMail($entretien->getResponsable()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerObservations(EntretienProfessionnel $entretien) : Mail
    {
        $vars = ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent(), 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("ENTRETIEN_OBSERVATION_AGENT", $vars);
        $mail = $this->getMailService()->sendMail($entretien->getResponsable()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }
}