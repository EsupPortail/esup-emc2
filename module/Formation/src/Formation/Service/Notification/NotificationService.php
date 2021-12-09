<?php

namespace Formation\Service\Notification;

use Application\Entity\Db\Agent;
use DateTime;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\Url\UrlServiceAwareTrait;
use UnicaenMail\Entity\Db\Mail;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

class NotificationService {
    use FormationInstanceServiceAwareTrait;
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

    /** Mails de rappel ***********************************************************************************************/

    public function triggerRappelAgentAvantFormation(FormationInstance $instance) : array
    {
        $vars = [
            'instance' => $instance,
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("FORMATION_INSCRIPTION_RAPPEL_AVANT_FORMATION", $vars);

        $liste = $instance->getListePrincipale();
        $mails = [];
        foreach ($liste as $inscrit) {
            $agent = $inscrit->getAgent();
            $mail = $this->getMailService()->sendMail($agent->getEmail(), $rendu->getSujet(), $rendu->getCorps());
            $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag(), $agent->generateTag() ]);
            $this->getMailService()->update($mail);
            $mails[] = $mail;
        }

        return $mails;
    }

    /**
     * @return Mail|null
     */
    public function triggerNotificationFormationsOuvertes() : ?Mail
    {
        /** @var FormationInstance[] $instances */
        $instances = $this->getFormationInstanceService()->getNouvelleInstance();

        if (! empty($instances)) {
            $texte = "<ul>";
            foreach ($instances as $instance) {
                $texte .= "<li>" . $instance->getInstanceLibelle() . " : " . $instance->getPeriode() . "</li>";
            }
            $texte .= "</ul>";

            $email = $this->getParametreService()->getParametreByCode('GLOBAL','MAIL_LISTE_BIATS')->getValeur();
            $vars = [
                'UrlService' => $this->getUrlService(),
            ];
            $rendu = $this->getRenduService()->genereateRenduByTemplateCode("FORMATION_NOTIFICATION_NOUVELLE_FORMATION", $vars);
            $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), str_replace("###A REMPLACER###", $texte, $rendu->getCorps()));
            $mail->setMotsClefs(["NOTIFICATION_FORMATION_" . (new DateTime())->format('d/m/Y')]);
            $this->getMailService()->update($mail);
            return $mail;
        }

        return null;
    }
}