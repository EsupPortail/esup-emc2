<?php

namespace Formation\Service\Notification;

use Application\Entity\Db\Agent;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Service\Url\UrlServiceAwareTrait;
use UnicaenMail\Entity\Db\Mail;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

class NotificationService {
    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use UrlServiceAwareTrait;

    /** GESTION DES INSCRIPTIONS **************************************************************************************/

    public function triggerInscriptionAgent(Agent $agent, FormationInstance $instance) : Mail
    {
        $vars = [
            'agent' => $agent,
            'instance' => $instance,
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("FORMATION_INSCRIPTION_DEMANDE_AGENT", $vars);

        $email = $this->getParametreService()->getParametreByCode('FORMATION','EMAIL')->getValeur();
        $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerResponsableValidation(FormationInstanceInscrit $inscription) : Mail
    {
        $instance = $inscription->getInstance();
        $agent = $inscription->getAgent();

        $email = $this->getParametreService()->getParametreByCode('FORMATION','EMAIL')->getValeur();
        $email .= ",";
        $email .= $agent->getEmail();

        $urlService = $this->getUrlService();
        $urlService->setVariables(['instance' => $inscription->getInstance(),]);

        $vars = [
            'agent' => $inscription->getAgent(),
            'instance' => $inscription->getInstance(),
            'UrlService' => $urlService,
        ];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("FORMATION_INSCRIPTION_RESPONSABLE_VALIDATION", $vars);

        $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerResponsableRefus(FormationInstanceInscrit $inscription) : Mail
    {
        $instance = $inscription->getInstance();
        $agent = $inscription->getAgent();

        $email = $this->getParametreService()->getParametreByCode('FORMATION','EMAIL')->getValeur();
        $email .= ",";
        $email .= $agent->getEmail();

        $vars = [
            'agent' => $agent,
            'instance' => $instance,
            'inscrit' => $inscription,
        ];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("FORMATION_INSCRIPTION_RESPONSABLE_REFUS", $vars);

        $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerDrhValidation(FormationInstanceInscrit $inscription) : Mail
    {
        $instance = $inscription->getInstance();
        $agent = $inscription->getAgent();

        $vars = [
            'agent' => $agent,
            'instance' => $instance,
            'UrlService' => $this->getUrlService()
        ];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("FORMATION_INSCRIPTION_DRH_VALIDATION", $vars);

        $mail = $this->getMailService()->sendMail($agent->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerDrhRefus(FormationInstanceInscrit $inscription)
    {
        $instance = $inscription->getInstance();
        $agent = $inscription->getAgent();

        $vars = [
            'agent' => $agent,
            'instance' => $instance,
            'inscrit' => $inscription
        ];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("FORMATION_INSCRIPTION_DRH_REFUS", $vars);

        $mail = $this->getMailService()->sendMail($agent->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }
}