<?php

namespace EntretienProfessionnel\Service\Notification;

use Application\Entity\Db\Agent;
use Structure\Entity\Db\StructureAgentForce;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\Delegue;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\TemplateProvider;
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

    /** Notifications liées à une campagne d'entretiens professionnels ************************************************/

    public function triggerCampagneOuvertureDirections(Campagne $campagne) : Mail
    {
        $vars = ['campagne' => $campagne, 'UrlService' => $this->getUrlService()];

        $mail_DAC = $this->parametreService->getParametreByCode('ENTRETIEN_PROFESSIONNEL','MAIL_LISTE_DAC')->getValeur();
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(TemplateProvider::CAMPAGNE_OUVERTURE_DAC, $vars);
        $mailDac = $this->getMailService()->sendMail($mail_DAC, $rendu->getSujet(), $rendu->getCorps());
        $mailDac->setMotsClefs([$campagne->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mailDac);

        return $mailDac;
    }

    public function triggerCampagneOuverturePersonnels(Campagne $campagne) : Mail
    {
        $vars = ['campagne' => $campagne, 'UrlService' => $this->getUrlService()];

        $mail_BIATS = $this->parametreService->getParametreByCode('ENTRETIEN_PROFESSIONNEL','MAIL_LISTE_BIATS')->getValeur();
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(TemplateProvider::CAMPAGNE_OUVERTURE_BIATSS, $vars);
        $mailBiats = $this->getMailService()->sendMail($mail_BIATS, $rendu->getSujet(), $rendu->getCorps());
        $mailBiats->setMotsClefs([$campagne->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mailBiats);

        return $mailBiats;
    }

    /** Notification liées aux délégue-e-s ****************************************************************************/

    public function triggerCampagneDelegue(Delegue $delegue) : Mail
    {
        $agent = $delegue->getAgent();
        $campagne = $delegue->getCampagne();
        $structure = $delegue->getStructure();
        $vars = ['campagne' => $campagne, 'agent' => $agent, 'structure' => $structure, 'UrlService' => $this->getUrlService()];

        $email = $agent->getEmail();
        $rendu = $this->getRenduService()->generateRenduByTemplateCode('CAMPAGNE_DELEGUE', $vars);
        $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$campagne->generateTag(), $agent->generateTag(), $structure->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);
        return $mail;
    }

    /** Notifications liées à la convation à un entretien *************************************************************/

    public function triggerConvocationDemande(EntretienProfessionnel $entretien) : Mail
    {
        $vars = ['entretien' => $entretien, 'campagne' => $entretien->getCampagne()];
        $url = $this->getUrlService();
        $url->setVariables($vars);
        $vars['UrlService'] = $url;

        $rendu = $this->getRenduService()->generateRenduByTemplateCode("ENTRETIEN_CONVOCATION_ENVOI", $vars);
        $mail = $this->getMailService()->sendMail($entretien->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerConvocationAcceptation(EntretienProfessionnel $entretien) : Mail
    {
        $vars = ['entretien' => $entretien, 'campagne' => $entretien->getCampagne(), 'agent' => $entretien->getAgent()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode("ENTRETIEN_CONVOCATION_ACCEPTER", $vars);
        $mail = $this->getMailService()->sendMail($entretien->getResponsable()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    /** Notification liées aux validations de l'entretien professionnel d'un agent ************************************/

    public function triggerValidationResponsableEntretien(EntretienProfessionnel $entretien) : Mail
    {
        $this->getUrlService()->setVariables(['entretien' => $entretien]);
        $vars = ['agent' => $entretien->getAgent(), 'campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode("ENTRETIEN_VALIDATION_1-RESPONSABLE", $vars);
        $mail = $this->getMailService()->sendMail($entretien->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerValidationResponsableHierarchique(EntretienProfessionnel $entretien) : Mail
    {
        $this->getUrlService()->setVariables(['entretien' => $entretien]);
        $vars = ['agent' => $entretien->getAgent(), 'campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode("ENTRETIEN_VALIDATION_3-HIERARCHIE", $vars);
        $mail = $this->getMailService()->sendMail($entretien->getResponsable()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerObservations(EntretienProfessionnel $entretien) : Mail
    {
        $agent = $entretien->getAgent();
        $autoriteMails = [];
        /** @var Agent $autorite */
        foreach ($this->getAgentService()->getResponsablesHierarchiques($agent) as $autorite) {
            $autoriteMails[] = $autorite->getEmail();
        }

        $this->getUrlService()->setVariables(['entretien' => $entretien]);
        $vars = ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent(), 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode("ENTRETIEN_VALIDATION_2-OBSERVATION", $vars);
        $mail = $this->getMailService()->sendMail($autoriteMails, $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        $rendu = $this->getRenduService()->generateRenduByTemplateCode("ENTRETIEN_VALIDATION_2-OBSERVATION_TRANSMISSION", $vars);
        $mail = $this->getMailService()->sendMail($entretien->getResponsable()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);
        return $mail;
    }

    public function triggerPasObservations(EntretienProfessionnel $entretien) : Mail
    {
        $agent = $entretien->getAgent();
        $autoriteMails = [];
        /** @var Agent $autorite */
        foreach ($this->getAgentService()->getResponsablesHierarchiques($agent) as $autorite) {
            $autoriteMails[] = $autorite->getEmail();
        }

        $this->getUrlService()->setVariables(['entretien' => $entretien]);
        $vars = ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent(), 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode("ENTRETIEN_VALIDATION_2-PAS_D_OBSERVATION", $vars);
        $mail = $this->getMailService()->sendMail($autoriteMails, $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerRappelEntretien(?EntretienProfessionnel $entretien) : Mail
    {
        $vars = ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent(), 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode("ENTRETIEN_RAPPEL_AGENT", $vars);
        $mail = $this->getMailService()->sendMail($entretien->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
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


        $entretiensProgrammes = $this->getCampagneService()->getAgentsAvecEntretiensEnCours($campagne, $allAgents);
        $entretiensFinalises  = $this->getCampagneService()->getAgentsAvecEntretiensFinalises($campagne, $allAgents);
        $entretiensAucuns     = count($allAgents) - count($entretiensFinalises) - count($entretiensProgrammes);
        $total = count($allAgents);

        $texte = "";
        if ($total !== 0) {
            $texte .= "<table style='width:80%; border: 1px solid black;border-collapse: collapse;font-weight:bold;' id='avancement'>";
            $texte .= "<caption> Avancement de la campagne 2027/2028</caption>";
            $texte .= "<tr>";
            $texte .= "<td style='background: lightgreen; width:" . (count($entretiensFinalises) / $total * 100)  . "%;'>" . count($entretiensFinalises) . "/" . $total . "</td>";
            $texte .= "<td style='background: #ffff9e; width:" .    (count($entretiensProgrammes) / $total * 100) . "%;'>" . count($entretiensProgrammes) . "/" . $total . "</td>";
            $texte .= "<td style='background: salmon; width:" .     ($entretiensAucuns / $total * 100)     . "%;'>" . $entretiensAucuns . "/" . $total . "</td>";
            $texte .= "</tr>";
            $texte .= "</table>";
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
        $rendu = $this->getRenduService()->generateRenduByTemplateCode("CAMPAGNE_AVANCEMENT", $vars);
        $mail = $this->getMailService()->sendMail(implode(',', $emails), $rendu->getSujet(), str_replace("###A REMPLACER###", $texte, $rendu->getCorps()));
        $mail->setMotsClefs(["NOTIFICATION_FORMATION_" . (new DateTime())->format('d/m/Y')]);
        $this->getMailService()->update($mail);
        return $mail;
    }
}