<?php

namespace FichePoste\Service\Notification;

use Application\Entity\Db\AgentSuperieur;
use Application\Entity\Db\FichePoste;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\Url\UrlServiceAwareTrait;
use FichePoste\Provider\Template\MailTemplates;
use UnicaenMail\Entity\Db\Mail;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenValidation\Entity\Db\ValidationInstance;

class NotificationService
{
    use AgentServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;

    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use UrlServiceAwareTrait;

    /** Méthodes de récupération des adresses électroniques ***********************************************************/

    /** Retourne l'adresse electronique de l'agent */
    public function getEmailAgent(?FichePoste $ficheposte): ?string
    {
        $emails = [];
        if ($ficheposte !== null) {
            $agent = $ficheposte->getAgent();
            if ($agent and $agent->getEmail()) $emails[] = $agent->getEmail();
        }
        return implode(',',$emails);
    }

    /** Retourne l'adresse electronique du responsable d'entretien */
    public function getEmailResponsable(?FichePoste $ficheposte): ?string
    {
        $responsables = $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($ficheposte->getAgent());
        $emails = array_map(
            function (AgentSuperieur $a) {
                return $a->getSuperieur()->getEmail();
            },
            $responsables
        );
        return implode(',',$emails);
    }

    /** Notifications liées aux validations de la fiche de poste ******************************************************/

    public function triggerValidationResponsableFichePoste(FichePoste $ficheposte, ?ValidationInstance $validation): Mail
    {
        $vars = ['ficheposte' => $ficheposte, 'agent' => $ficheposte->getAgent(), 'validation' => $validation];
        $UrlService = $this->getUrlService()->setVariables($vars);
        $vars['UrlService'] = $UrlService;

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FICHE_POSTE_VALIDATION_RESPONSABLE, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($ficheposte), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$ficheposte->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerRefusFichePoste(FichePoste $ficheposte, ?ValidationInstance $validation): Mail
    {
        $vars = ['ficheposte' => $ficheposte, 'agent' => $ficheposte->getAgent(), 'validation' => $validation];
        $UrlService = $this->getUrlService()->setVariables($vars);
        $vars['UrlService'] = $UrlService;

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FICHE_POSTE_VALIDATION_RESPONSABLE, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($ficheposte), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$ficheposte->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerValidationAgentFichePoste(FichePoste $ficheposte, ?ValidationInstance $validation): Mail
    {

        $vars = ['ficheposte' => $ficheposte, 'agent' => $ficheposte->getAgent(), 'validation' => $validation];
        $UrlService = $this->getUrlService()->setVariables($vars);
        $vars['UrlService'] = $UrlService;

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FICHE_POSTE_VALIDATION_AGENT, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailResponsable($ficheposte), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$ficheposte->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerValidationDrhFichePoste(FichePoste $ficheposte, ?ValidationInstance $validation): Mail
    {

        $vars = ['ficheposte' => $ficheposte, 'agent' => $ficheposte->getAgent(), 'validation' => $validation];
        $UrlService = $this->getUrlService()->setVariables($vars);
        $vars['UrlService'] = $UrlService;

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FICHE_POSTE_VALIDATION_DRH, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailResponsable($ficheposte), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$ficheposte->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }
}