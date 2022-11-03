<?php

namespace Application\Service\Notification;

use Application\Entity\Db\FichePoste;
use Application\Provider\Template\MailTemplates;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Url\UrlServiceAwareTrait;
use UnicaenMail\Entity\Db\Mail;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenValidation\Entity\Db\ValidationInstance;

class NotificationService
{
    use AgentServiceAwareTrait;
    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use UrlServiceAwareTrait;

    /** Méthodes de récupération des adresses électroniques ***********************************************************/

    /**
     * Retourne l'adresse electronique de l'agent
     * @param FichePoste|null $ficheposte
     * @return string[]
     */
    public function getEmailAgent(?FichePoste $ficheposte): array
    {
        $emails = [];
        if ($ficheposte !== null) {
            $agent = $ficheposte->getAgent();
            if ($agent and $agent->getEmail()) $emails[] = $agent->getEmail();
        }
        return $emails;
    }

    /**
     * Retourne l'adresse electronique du responsable d'entretien
     * @param FichePoste|null $ficheposte
     * @return string[]
     */
    public function getEmailResponsable(?FichePoste $ficheposte): array
    {
        $emails = [];
        if ($ficheposte !== null) {
            $agent = $ficheposte->getAgent();
            $responsables = $this->getAgentService()->getResposabiliteStructure($agent);
            foreach ($responsables as $responsable) {
                if ($responsable and $responsable->getAgent()->getEmail()) $emails[] = $responsable->getAgent()->getEmail();
            }
        }
        return $emails;
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
}