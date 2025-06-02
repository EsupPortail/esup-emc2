<?php

namespace EntretienProfessionnel\Service\Notification;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\Macro\MacroServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Observation\EntretienProfessionnelObservations;
use EntretienProfessionnel\Provider\Parametre\EntretienProfessionnelParametres;
use EntretienProfessionnel\Provider\Template\MailTemplates;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Ics\IcsServiceAwareTrait;
use EntretienProfessionnel\Service\Url\UrlServiceAwareTrait;
use EntretienProfessionnel\View\Helper\CampagneAvancementViewHelper;
use Exception;
use Laminas\View\Renderer\PhpRenderer;
use RuntimeException;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenMail\Entity\Db\Mail;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

class NotificationService extends \Application\Service\Notification\NotificationService
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use IcsServiceAwareTrait;
    use MailServiceAwareTrait;
    use MacroServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UrlServiceAwareTrait;

    public ?PhpRenderer $renderer = null;

    /** Méthodes de récupération des adresses électroniques ************************************************************
     * Ces méthodes sont à favoriser, car permettent un refactoring plus rapide
     **/

    /** Retourne l'adresse de l'agent·e **/
    public function getEmailAgent(?EntretienProfessionnel $entretienProfessionnel): ?string
    {
        $emails = [];
        if ($entretienProfessionnel !== null) {
            $agent = $entretienProfessionnel->getAgent();
            if ($agent and $agent->getEmail()) $emails[] = $agent->getEmail();
        }
        return implode(',', $emails);
    }

    /** Retourne l'adresse du reponsable de l'entretien professionnel */
    public function getEmailResponsable(?EntretienProfessionnel $entretienProfessionnel): ?string
    {
        $emails = [];
        if ($entretienProfessionnel !== null) {
            $agent = $entretienProfessionnel->getResponsable();
            if ($agent and !$agent->isDeleted() and $agent->getEmail()) $emails[] = $agent->getEmail();
        }
        return implode(',', $emails);
    }

    /** Retourne l'adresse DES supérieures hiérarchiques de l'agent **/

    public function getEmailSuperieursHierarchiques(?EntretienProfessionnel $entretienProfessionnel): ?string
    {
        $agent = $entretienProfessionnel->getAgent();
        $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent);

        $emails = [];
        foreach ($superieurs as $superieur) {
            if (!$superieur->getSuperieur()->isDeleted()) $emails[] = $superieur->getSuperieur()->getEmail();
        }
        return implode(',', $emails);
    }

    /** Retourne l'adresse DES autorités hiérarchiques de l'agent */
    public function getEmailAutoritesHierarchiques(?EntretienProfessionnel $entretienProfessionnel): ?string
    {
        $agent = $entretienProfessionnel->getAgent();
        $autorites = $this->getAgentAutoriteService()->getAgentsAutoritesByAgent($agent);

        $emails = [];
        foreach ($autorites as $autorite) {
            if (!$autorite->getAutorite()->isDeleted()) $emails[] = $autorite->getAutorite()->getEmail();
        }
        return implode(',', $emails);
    }



    /** Récupération des variables ************************************************************************************/

    public function computeVariableFromCampagne(Campagne $campagne): array
    {
        $vars = [
            'campagne' => $campagne,
            'UrlService' => $this->getUrlService(),
            'MacroService' => $this->getMacroService(),
        ];
        return $vars;
    }

    public function computeVariableFromEntretienProfessionnel(EntretienProfessionnel $entretien): array
    {
        $vars = [
            'entretien' => $entretien,
            'agent' => $entretien->getAgent(),
            'campagne' => $entretien->getCampagne(),
            'MacroService' => $this->getMacroService()
        ];
        $url = $this->getUrlService();
        $url->setVariables($vars);
        $vars['UrlService'] = $url;

        return $vars;
    }

    /** Notifications liées à une campagne d'entretiens professionnels ************************************************/

    public function triggerCampagneOuvertureDirections(Campagne $campagne): Mail
    {
        $vars = $this->computeVariableFromCampagne($campagne);

        try {
            $mail_DAC = $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::MAIL_LISTE_DAC);
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération d'un paramètre", 0, $e);
        }
        if ($mail_DAC === null OR $mail_DAC === '') {
            throw new RuntimeException("Aucune adresse pour notifier les responsables. Veuillez vérifier que le parametre [".EntretienProfessionnelParametres::TYPE."|".EntretienProfessionnelParametres::MAIL_LISTE_DAC."] est bien déclaré et à une valeur correcte.");
        }
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::CAMPAGNE_OUVERTURE_DAC, $vars);
        $mailDac = $this->getMailService()->sendMail($mail_DAC, $rendu->getSujet(), $rendu->getCorps(), 'EntretienProfessionnel');
        $mailDac->setMotsClefs([$campagne->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mailDac);

        return $mailDac;
    }

    public function triggerCampagneOuverturePersonnels(Campagne $campagne): Mail
    {
        $vars = $this->computeVariableFromCampagne($campagne);

        try {
            $mail_BIATS = $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::MAIL_LISTE_BIATS);
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération d'un paramètre", 0, $e);
        }
        if ($mail_BIATS === null OR $mail_BIATS === '') {
            throw new RuntimeException("Aucune adresse pour notifier les responsables. Veuillez vérifier que le parametre [".EntretienProfessionnelParametres::TYPE."|".EntretienProfessionnelParametres::MAIL_LISTE_BIATS."] est bien déclaré et à une valeur correcte.");
        }
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::CAMPAGNE_OUVERTURE_BIATSS, $vars);
        $mailBiats = $this->getMailService()->sendMail($mail_BIATS, $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mailBiats->setMotsClefs([$campagne->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mailBiats);

        return $mailBiats;
    }

    /** Notifications liées à la convation à un entretien *************************************************************/

    public function triggerConvocationDemande(EntretienProfessionnel $entretien): Mail
    {
        $vars = $this->computeVariableFromEntretienProfessionnel($entretien);

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_CONVOCATION_ENVOI, $vars);
        $ics = $this->getIcsService()->generateInvitation($entretien);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel', $ics);
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerConvocationAcceptation(EntretienProfessionnel $entretien): Mail
    {
        $vars = $this->computeVariableFromEntretienProfessionnel($entretien);

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_CONVOCATION_ACCEPTER, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailResponsable($entretien), $rendu->getSujet(), $rendu->getCorps(), 'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerAnnulationEntretienProfessionnel(?EntretienProfessionnel $entretien)
    {
        $vars = $this->computeVariableFromEntretienProfessionnel($entretien);

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_ANNULATION, $vars);
        $ics = $this->getIcsService()->generateAnnulation($entretien);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel', $ics);
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    /** Notifications liées aux validations de l'entretien professionnel d'un agent ************************************/

    public function triggerValidationResponsableEntretien(EntretienProfessionnel $entretien): Mail
    {
        $vars  =$this->computeVariableFromEntretienProfessionnel($entretien);

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_1_RESPONSABLE, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerObservations(EntretienProfessionnel $entretien): Mail
    {
        $vars = $this->computeVariableFromEntretienProfessionnel($entretien);

        if ($entretien->hasObservationWithTypeCode(EntretienProfessionnelObservations::OBSERVATION_AGENT_ENTRETIEN)
            || $entretien->hasObservationWithTypeCode(EntretienProfessionnelObservations::OBSERVATION_AGENT_PERSPECTIVE)
            || $entretien->hasObservationWithTypeCode(EntretienProfessionnelObservations::OBSERVATION_AGENT_FORMATION)
            ) {
            $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_2_OBSERVATION, $vars);
            $mail = $this->getMailService()->sendMail($this->getEmailAutoritesHierarchiques($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
            $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
            $this->getMailService()->update($mail);

            $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_2_OBSERVATION_TRANSMISSION, $vars);
            $mail = $this->getMailService()->sendMail($this->getEmailResponsable($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
            $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
            $this->getMailService()->update($mail);
        } else {
            $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_2_PAS_D_OBSERVATION, $vars);
            $mail = $this->getMailService()->sendMail($this->getEmailAutoritesHierarchiques($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
            $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
            $this->getMailService()->update($mail);
        }
        return $mail;
    }

    public function triggerPasObservations(EntretienProfessionnel $entretien): Mail
    {
        $vars = $this->computeVariableFromEntretienProfessionnel($entretien);

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_2_PAS_D_OBSERVATION, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAutoritesHierarchiques($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerValidationResponsableHierarchique(EntretienProfessionnel $entretien): Mail
    {
        $vars = $this->computeVariableFromEntretienProfessionnel($entretien);

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_3_HIERARCHIE, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerValidationAgent(EntretienProfessionnel $entretien): Mail
    {
        $vars = $this->computeVariableFromEntretienProfessionnel($entretien);

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_4_AGENT, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailResponsable($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerRappelEntretien(?EntretienProfessionnel $entretien): Mail
    {
        $vars = $this->computeVariableFromEntretienProfessionnel($entretien);

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::RAPPEL_ENTRETIEN_AGENT, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerRappelCampagneSuperieur(Campagne $campagne, Agent $superieur): ?Mail
    {
        $agents = array_map(function (AgentSuperieur $a) {
            return $a->getAgent();
        }, $this->getAgentSuperieurService()->getAgentsSuperieursBySuperieur($superieur));
        [$obligatoires, $facultatifs, $raisons] = $this->getCampagneService()->trierAgents($campagne, $agents);
        $entretiens = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents);
        $agents = $obligatoires;
        foreach ($entretiens as $entretien) {
            $agent = $entretien->getAgent();
            $agents[$agent->getId()] = $agent;
        }

        $notificationNeeded = false;
        foreach ($obligatoires as $obligatoire) {
            if (!isset($entretiens[$obligatoire->getId()]) || !$entretiens[$obligatoire->getId()]->isEtatActif(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT)) {
                $notificationNeeded = true;
                break;
            }
        }
        if (!$notificationNeeded) return null;

        $vh = new CampagneAvancementViewHelper();
        $vh->renderer = $this->renderer;
        $vh->entretiens = $entretiens;
        $vh->agents = $agents;
        $texte = $vh->render('table');

        $vars = [
            'campagne' => $campagne,
            'agent' => $superieur,
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::RAPPEL_CAMPAGNE_AVANCEMENT_SUPERIEUR, $vars);
        $mail = $this->getMailService()->sendMail($superieur->getEmail(), $rendu->getSujet(), str_replace("###A REMPLACER###", $texte, $rendu->getCorps()),'EntretienProfessionnel');
        $mail->setMotsClefs(["CAMPAGNE_" . $campagne->getId(), "CAMPAGNE_AVANCEMENT", "DATE_" . (new DateTime())->format('d/m/Y')]);
        $this->getMailService()->update($mail);
        return $mail;
    }

    public function triggerRappelCampagneAutorite(Campagne $campagne, Agent $autorite): ?Mail
    {
        $agents = array_map(function (AgentAutorite $a) {
            return $a->getAgent();
        }, $this->getAgentAutoriteService()->getAgentsAutoritesByAutorite($autorite));

        [$obligatoires, $facultatifs, $raisons] = $this->getCampagneService()->trierAgents($campagne, $agents);
        $entretiens = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents);
        $agents = $obligatoires;
        foreach ($entretiens as $entretien) {
            $agent = $entretien->getAgent();
            $agents[$agent->getId()] = $agent;
        }

        $notificationNeeded = false;
        foreach ($obligatoires as $obligatoire) {
            if (!isset($entretiens[$obligatoire->getId()])  && !$entretiens[$obligatoire->getId()]->isEtatActif(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT)) {
                $notificationNeeded = true;
                break;
            }
        }

        if ($notificationNeeded === false) return null;

        $vh = new CampagneAvancementViewHelper();
        $vh->renderer = $this->renderer;
        $vh->entretiens = $entretiens;
        $vh->agents = $agents;
        $texte = $vh->render('table');

        $vars = [
            'campagne' => $campagne,
            'agent' => $autorite,
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::RAPPEL_CAMPAGNE_AVANCEMENT_AUTORITE, $vars);
        $mail = $this->getMailService()->sendMail($autorite->getEmail(), $rendu->getSujet(), str_replace("###A REMPLACER###", $texte, $rendu->getCorps()),'EntretienProfessionnel');
        $mail->setMotsClefs(["CAMPAGNE_" . $campagne->getId(), "CAMPAGNE_AVANCEMENT", "DATE_" . (new DateTime())->format('d/m/Y')]);
        $this->getMailService()->update($mail);
        return $mail;
    }

    /** Notifications associées à la demande de validation (en retard) d'entretiens professionnels *****/

    public function triggerRappelValidationSuperieur(Agent $superieur, Campagne $campagne, array $entretiens): Mail
    {
        $vars = ['campagne' => $campagne, 'agent' => $superieur, 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::RAPPEL_ATTENTE_VALIDATION_SUPERIEUR, $vars);

        $texte = "<ul>";
        foreach ($entretiens as $entretien) $texte .= "<li>" . $entretien->getAgent()->getDenomination() . "</li>";
        $texte .= "</ul>";

        $corps = $rendu->getCorps();
        $corps = str_replace('###SERA REMPLACÉ###', $texte, $corps);
        $mail = $this->getMailService()->sendMail($superieur->getEmail(), $rendu->getSujet(), $corps,'EntretienProfessionnel');
        $mail->setMotsClefs([$campagne->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerRappelValidationAutorite(Agent $autorite, Campagne $campagne, array $entretiens): Mail
    {
        $vars = ['campagne' => $campagne, 'agent' => $autorite, 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::RAPPEL_ATTENTE_VALIDATION_AUTORITE, $vars);

        $texte = "<ul>";
        foreach ($entretiens as $entretien) $texte .= "<li>" . $entretien->getAgent()->getDenomination() . "</li>";
        $texte .= "</ul>";

        $corps = $rendu->getCorps();
        $corps = str_replace('###SERA REMPLACÉ###', $texte, $corps);
        $mail = $this->getMailService()->sendMail($autorite->getEmail(), $rendu->getSujet(), $corps,'EntretienProfessionnel');
        $mail->setMotsClefs([$campagne->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerRappelValidationAgent(?EntretienProfessionnel $entretien): Mail
    {
        $vars = $this->computeVariableFromEntretienProfessionnel($entretien);

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::RAPPEL_ATTENTE_VALIDATION_AGENT, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->getCampagne()->generateTag(), $entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    /** NOTIFICATIONS ASSOCIEES AUX PROCEDURES POST-ENTRETIEN *********************************************************/

    public function triggerModificationComptesRendus(EntretienProfessionnel $entretien): Mail
    {
        $vars = $this->computeVariableFromEntretienProfessionnel($entretien);

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::MODIFICATIONS_APPORTEES_AUX_CRS, $vars);
        $mail_array = [];
        foreach ($this->getEmailAgent($entretien) as $mail) {
            $mail_array[] = $mail;
        }
        foreach ($this->getEmailSuperieursHierarchiques($entretien) as $mail) {
            $mail_array[] = $mail;
        }
        foreach ($this->getEmailAutoritesHierarchiques($entretien) as $mail) {
            $mail_array[] = $mail;
        }
        $mail_array = array_unique($mail_array);
        $mails  = implode(",", $mail_array);

        $mail = $this->getMailService()->sendMail($mails, $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->getCampagne()->generateTag(), $entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);
        return $mail;
    }


}