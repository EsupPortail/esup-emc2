<?php

namespace EntretienProfessionnel\Service\Notification;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\Observation\EntretienProfessionnelObservations;
use EntretienProfessionnel\Provider\Parametre\EntretienProfessionnelParametres;
use EntretienProfessionnel\Provider\Template\MailTemplates;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
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

class NotificationService
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UrlServiceAwareTrait;

    public ?PhpRenderer $renderer = null;

    /** Méthodes de récupération des adresses électroniques ***********************************************************/

    /**
     * Retourne l'adresse electronique de l'agent
     * @param EntretienProfessionnel|null $entretienProfessionnel
     * @return string[]
     */
    public function getEmailAgent(?EntretienProfessionnel $entretienProfessionnel): array
    {
        $emails = [];
        if ($entretienProfessionnel !== null) {
            $agent = $entretienProfessionnel->getAgent();
            if ($agent and $agent->getEmail()) $emails[] = $agent->getEmail();
        }
        return $emails;
    }

    /**
     * Retourne l'adresse electronique du responsable d'entretien
     * @param EntretienProfessionnel|null $entretienProfessionnel
     * @return string[]
     */
    public function getEmailResponsable(?EntretienProfessionnel $entretienProfessionnel): array
    {
        $emails = [];
        if ($entretienProfessionnel !== null) {
            $agent = $entretienProfessionnel->getResponsable();
            if ($agent and !$agent->isDeleted() and $agent->getEmail()) $emails[] = $agent->getEmail();
        }
        return $emails;
    }

    /**
     * Retourne l'adresse des supérieures hiérarchiques de l'agent
     * @param EntretienProfessionnel|null $entretienProfessionnel
     * @return string[]
     */
    public function getEmailSuperieursHierarchiques(?EntretienProfessionnel $entretienProfessionnel): array
    {
        $agent = $entretienProfessionnel->getAgent();
        $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent);

        $emails = [];
        foreach ($superieurs as $superieur) {
            if (!$superieur->getSuperieur()->isDeleted()) $emails[] = $superieur->getSuperieur()->getEmail();
        }
        return $emails;
    }

    /**
     * Retourne l'adresse des autorités hiérarchiques de l'agent
     * @param EntretienProfessionnel|null $entretienProfessionnel
     * @return string[]
     */
    public function getEmailAutoritesHierarchiques(?EntretienProfessionnel $entretienProfessionnel): array
    {
        $agent = $entretienProfessionnel->getAgent();
        $autorites = $this->getAgentAutoriteService()->getAgentsAutoritesByAgent($agent);

        $emails = [];
        foreach ($autorites as $autorite) {
            if (!$autorite->getAutorite()->isDeleted()) $emails[] = $autorite->getAutorite()->getEmail();
        }
        return $emails;
    }

    /** Notifications liées à une campagne d'entretiens professionnels ************************************************/

    public function triggerCampagneOuvertureDirections(Campagne $campagne): Mail
    {
        $vars = ['campagne' => $campagne, 'UrlService' => $this->getUrlService()];

        try {
            $mail_DAC = $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::MAIL_LISTE_DAC);
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération d'un paramètre", 0, $e);
        }
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::CAMPAGNE_OUVERTURE_DAC, $vars);
        $mailDac = $this->getMailService()->sendMail($mail_DAC, $rendu->getSujet(), $rendu->getCorps(), 'EntretienProfessionnel');
        $mailDac->setMotsClefs([$campagne->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mailDac);

        return $mailDac;
    }

    public function triggerCampagneOuverturePersonnels(Campagne $campagne): Mail
    {
        $vars = ['campagne' => $campagne, 'UrlService' => $this->getUrlService()];

        try {
            $mail_BIATS = $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::MAIL_LISTE_BIATS);
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération d'un paramètre", 0, $e);
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
        $vars = ['entretien' => $entretien, 'campagne' => $entretien->getCampagne()];
        $url = $this->getUrlService();
        $url->setVariables($vars);
        $vars['UrlService'] = $url;

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_CONVOCATION_ENVOI, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerConvocationAcceptation(EntretienProfessionnel $entretien): Mail
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

    /** Notifications liées aux validations de l'entretien professionnel d'un agent ************************************/

    public function triggerValidationResponsableEntretien(EntretienProfessionnel $entretien): Mail
    {
        $this->getUrlService()->setVariables(['entretien' => $entretien]);
        $vars = ['agent' => $entretien->getAgent(), 'campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_1_RESPONSABLE, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerObservations(EntretienProfessionnel $entretien): Mail
    {
        $this->getUrlService()->setVariables(['entretien' => $entretien]);
        $vars = ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent(), 'UrlService' => $this->getUrlService()];

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
        $this->getUrlService()->setVariables(['entretien' => $entretien]);
        $vars = ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent(), 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_2_PAS_D_OBSERVATION, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAutoritesHierarchiques($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerValidationResponsableHierarchique(EntretienProfessionnel $entretien): Mail
    {
        $this->getUrlService()->setVariables(['entretien' => $entretien]);
        $vars = ['agent' => $entretien->getAgent(), 'campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_3_HIERARCHIE, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerValidationAgent(EntretienProfessionnel $entretien): Mail
    {
        $this->getUrlService()->setVariables(['entretien' => $entretien]);
        $vars = ['agent' => $entretien->getAgent(), 'campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::ENTRETIEN_VALIDATION_4_AGENT, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailResponsable($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerRappelEntretien(?EntretienProfessionnel $entretien): Mail
    {
        $vars = ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent(), 'UrlService' => $this->getUrlService()];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::RAPPEL_ENTRETIEN_AGENT, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerRappelCampagneSuperieur(Campagne $campagne, Agent $superieur): Mail
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

    public function triggerRappelCampagneAutorite(Campagne $campagne, Agent $autorite): Mail
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

    /** Notifications associées à la demande de validation (en retard) d'entretiens profesionnels *****/

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
        $vars = ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent()];
        $urlService = $this->getUrlService(); $urlService->setVariables($vars);
        $vars['UrlService'] = $this->getUrlService();

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::RAPPEL_ATTENTE_VALIDATION_AGENT, $vars);
        $mail = $this->getMailService()->sendMail($this->getEmailAgent($entretien), $rendu->getSujet(), $rendu->getCorps(),'EntretienProfessionnel');
        $mail->setMotsClefs([$entretien->getCampagne()->generateTag(), $entretien->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    /** NOTIFICATIONS ASSOCIEES AUX PROCEDURES POST-ENTRETIEN *********************************************************/

    public function triggerModificationComptesRendus(EntretienProfessionnel $entretien): Mail
    {
        $vars = ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent()];
        $this->getUrlService()->setVariables($vars);
        $vars['UrlService'] = $this->getUrlService();

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