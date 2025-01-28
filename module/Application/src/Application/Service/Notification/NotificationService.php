<?php

namespace Application\Service\Notification;

use Application\Entity\Db\AgentSuperieur;
use Application\Entity\Db\FichePoste;
use Application\Provider\Template\MailTemplates;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\Url\UrlServiceAwareTrait;
use UnicaenMail\Entity\Db\Mail;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

class NotificationService
{
    use AgentServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;

    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use UrlServiceAwareTrait;

    /** Méthodes de récupération des adresses électroniques ***********************************************************/

    public function getEmailAgent(?FichePoste $ficheposte): ?string
    {
        $emails = [];
        if ($ficheposte !== null) {
            $agent = $ficheposte->getAgent();
            if ($agent and $agent->getEmail()) $emails[] = $agent->getEmail();
        }
        return implode(',', $emails);
    }

    public function getEmailResponsable(?FichePoste $ficheposte): ?string
    {
        $responsables = $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($ficheposte->getAgent());
        if (empty($responsables)) return null;
        $email = array_map(
            function (AgentSuperieur $a) { return $a->getSuperieur()->getEmail();},
            $responsables
        );
        return implode(',', $email);
    }
}