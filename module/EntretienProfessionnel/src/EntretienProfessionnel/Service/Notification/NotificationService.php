<?php

namespace EntretienProfessionnel\Service\Notification;

use Application\Entity\Db\Agent;
use DateInterval;
use EntretienProfessionnel\Provider\Template\MailTemplates;
use Structure\Entity\Db\StructureAgentForce;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\Url\UrlServiceAwareTrait;
use Structure\Entity\Db\Structure;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenMail\Entity\Db\Mail;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

class NotificationService {
    use AgentServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UrlServiceAwareTrait;

    /** Méthodes de récupération des adresses électroniques ***********************************************************/

    /**
     * Retourne l'adresse electronique de l'agent
     * @param EntretienProfessionnel|null $entretienProfessionnel
     * @return string[]
     */
    public function getEmailAgent(?EntretienProfessionnel $entretienProfessionnel) : array
    {
        $emails = [];
        if ($entretienProfessionnel !== null) {
            $agent = $entretienProfessionnel->getAgent();
            if ($agent AND $agent->getEmail()) $emails[] = $agent->getEmail();
        }
        return $emails;
    }

    /**
     * Retourne l'adresse electronique du responsable d'entretien
     * @param EntretienProfessionnel|null $entretienProfessionnel
     * @return string[]
     */
    public function getEmailResponsable(?EntretienProfessionnel $entretienProfessionnel) : array
    {
        $emails = [];
        if ($entretienProfessionnel !== null) {
            $agent = $entretienProfessionnel->getResponsable();
            if ($agent AND $agent->getEmail()) $emails[] = $agent->getEmail();
        }
        return $emails;
    }

    /**
     * Retourne l'adresse des supérieures hiérarchiques de l'agent
     * @param EntretienProfessionnel|null $entretienProfessionnel
     * @return string[]
     */
    public function getEmailSuperieursHierarchiques(?EntretienProfessionnel $entretienProfessionnel) : array
    {
        $agent = $entretienProfessionnel->getAgent();
        $superieurs =  $this->getAgentService()->computeSuperieures($agent);

        $emails = [];
        foreach ($superieurs as $superieur) {
            $emails[] = $superieur->getEmail();
        }
        sort($emails);
        return $emails;
    }

    /**
     * Retourne l'adresse des autorités hiérarchiques de l'agent
     * @param EntretienProfessionnel|null $entretienProfessionnel
     * @return string[]
     */
    public function getEmailAutoritesHierarchiques(?EntretienProfessionnel $entretienProfessionnel) : array
    {
        $agent = $entretienProfessionnel->getAgent();
        $autorites = $this->getAgentService()->computeAutorites($agent);

        $emails = [];
        foreach ($autorites as $autorite) {
            $emails[] = $autorite->getEmail();
        }
        return $emails;
    }

    /** Notifications liées à une campagne d'entretiens professionnels ************************************************/

    public function triggerCampagneOuvertureDirections(Campagne $campagne) : Mail
    {
        $vars = ['campagne' => $campagne, 'UrlService' => $this->getUrlService()];

        $mail_DAC = $this->parametreService->getParametreByCode('ENTRETIEN_PROFESSIONNEL','MAIL_LISTE_DAC')->getValeur();
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::CAMPAGNE_OUVERTURE_DAC, $vars);
        $mailDac = $this->getMailService()->sendMail($mail_DAC, $rendu->getSujet(), $rendu->getCorps());
        $mailDac->setMotsClefs([$campagne->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mailDac);

        return $mailDac;
    }

    public function triggerCampagneOuverturePersonnels(Campagne $campagne) : Mail
    {
        $vars = ['campagne' => $campagne, 'UrlService' => $this->getUrlService()];

        $mail_BIATS = $this->parametreService->getParametreByCode('ENTRETIEN_PROFESSIONNEL','MAIL_LISTE_BIATS')->getValeur();
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::CAMPAGNE_OUVERTURE_BIATSS, $vars);
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

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_CONVOCATION_ENVOI, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerConvocationAcceptation(EntretienProfessionnel $entretien) : Mail
    {
        $vars = [
            'entretien' => $entretien,
            'campagne' => $entretien->getCampagne(),
            'agent' => $entretien->getAgent(),
            'UrlService' => $this->getUrlService()
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_CONVOCATION_ACCEPTER, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailResponsable($entretien), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    /** Notification liées aux validations de l'entretien professionnel d'un agent ************************************/

    public function triggerValidationResponsableEntretien(EntretienProfessionnel $entretien) : Mail
    {
        $this->getUrlService()->setVariables(['entretien' => $entretien]);
        $vars = ['agent' => $entretien->getAgent(), 'campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_1_RESPONSABLE, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerObservations(EntretienProfessionnel $entretien) : Mail
    {
        $this->getUrlService()->setVariables(['entretien' => $entretien]);
        $vars = ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent(), 'UrlService' => $this->getUrlService()];

        if ($entretien->getObservationActive() !== null) {
            $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_2_OBSERVATION, $vars);
            $mail = $this->getMailService()->sendMail($this->getEmailAutoritesHierarchiques($entretien), $rendu->getSujet(), $rendu->getCorps());
            $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
            $this->getMailService()->update($mail);

            $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_2_OBSERVATION_TRANSMISSION, $vars);
            $mail = $this->getMailService()->sendMail($this->getEmailResponsable($entretien), $rendu->getSujet(), $rendu->getCorps());
            $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
            $this->getMailService()->update($mail);
        } else {
            $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_2_PAS_D_OBSERVATION, $vars);
            $mail = $this->getMailService()->sendMail($this->getEmailAutoritesHierarchiques($entretien), $rendu->getSujet(), $rendu->getCorps());
            $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
            $this->getMailService()->update($mail);
        }
        return $mail;
    }

    public function triggerPasObservations(EntretienProfessionnel $entretien) : Mail
    {
        $this->getUrlService()->setVariables(['entretien' => $entretien]);
        $vars = ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent(), 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_2_PAS_D_OBSERVATION, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAutoritesHierarchiques($entretien), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerValidationResponsableHierarchique(EntretienProfessionnel $entretien) : Mail
    {
        $this->getUrlService()->setVariables(['entretien' => $entretien]);
        $vars = ['agent' => $entretien->getAgent(), 'campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_3_HIERARCHIE, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerRappelEntretien(?EntretienProfessionnel $entretien) : Mail
    {
        $vars = ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent(), 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::RAPPEL_ENTRETIEN_AGENT, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerRappelCampagne(Campagne $campagne, Structure $structure) : Mail
    {
        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] =  $structure;
        /** Récupération des agents et postes liés aux structures */
        $agents = $this->getAgentService()->getAgentsByStructures($structures);
        $agentsForces = array_map(function (StructureAgentForce $a) { return $a->getAgent(); }, $structure->getAgentsForces());
        $allAgents = array_merge($agents, $agentsForces);

        $date = DateTime::createFromFormat('d/m/Y',$campagne->getDateFin()->format('d/m/Y'));
        $date = $date->sub(new DateInterval('P12M'));

        $allAgents = array_filter($allAgents,
            function (Agent $a) use ($date) { return $a->getTControatLong() === 'O' AND !empty($a->getAffectationsActifs($date));}
        );

        $entretiensPlanifies = $this->getCampagneService()->getAgentsAvecEntretiensPlanifies($campagne, $allAgents);
        $entretiensFaits  = $this->getCampagneService()->getAgentsAvecEntretiensFaits($campagne, $allAgents);
        $entretiensFinalises  = $this->getCampagneService()->getAgentsAvecEntretiensFinalises($campagne, $allAgents);
        $entretiensAucuns     = count($allAgents) - count($entretiensFinalises) - count($entretiensPlanifies) - count($entretiensFaits);
        $total = count($allAgents);

        $texte = "";
        if ($total !== 0) {
            $texte .= "<table style='width:80%; border: 1px solid black;border-collapse: collapse;font-weight:bold;' id='avancement'>";
            $texte .= "<caption> Avancement de la campagne ".$campagne->getAnnee()."</caption>";
            $texte .= "<tr>";
            $texte .= "<td style='background: lightgreen; width:" . (count($entretiensFinalises) / $total * 100)  . "%;'>" . count($entretiensFinalises) . "/" . $total . "</td>";
            $texte .= "<td style='background: #ffff9e; width:" .    (count($entretiensFaits) / $total * 100) . "%;'>" . count($entretiensFaits) . "/" . $total . "</td>";
            $texte .= "<td style='background: #ffb939; width:" .    (count($entretiensPlanifies) / $total * 100) . "%;'>" . count($entretiensPlanifies) . "/" . $total . "</td>";
            $texte .= "<td style='background: salmon; width:" .     ($entretiensAucuns / $total * 100)     . "%;'>" . $entretiensAucuns . "/" . $total . "</td>";
            $texte .= "</tr>";
            $texte .= "</table>";
            $texte .= "<br/>";
            $texte .= "<table><tr><td style='background: lightgreen; border: 1px black solid;'>&nbsp;&nbsp;&nbsp;</td><td> Entretiens finalisés </td></tr></table>";
            $texte .= "<table><tr><td style='background: #ffff9e; border: 1px black solid;'>&nbsp;&nbsp;&nbsp;</td><td> Entretiens faits </td></tr></table>";
            $texte .= "<table><tr><td style='background: #ffb939; border: 1px black solid;'>&nbsp;&nbsp;&nbsp;</td><td> Entretiens planifiés </td></tr></table>";
            $texte .= "<table><tr><td style='background: salmon; border: 1px black solid;'>&nbsp;&nbsp;&nbsp;</td><td> Entretiens manquants </td></tr></table>";

        }

        $emails = [];
        foreach ($structure->getResponsables() as $responsable) {
            $emails[] = $responsable->getAgent()->getEmail();
        }
        $vars = [
            'campagne' => $campagne,
            'structure' => $structure,
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::RAPPEL_CAMPAGNE_AVANCEMENT, $vars);
        $mail = $this->getMailService()->sendMail(implode(',', $emails), $rendu->getSujet(), str_replace("###A REMPLACER###", $texte, $rendu->getCorps()));
        $mail->setMotsClefs(["CAMPAGNE_" .$campagne->getId(), "CAMPAGNE_AVANCEMENT" , "DATE_". (new DateTime())->format('d/m/Y')]);
        $this->getMailService()->update($mail);
        return $mail;
    }
}