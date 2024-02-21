<?php

namespace Formation\Service\Notification;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentSuperieur;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\Macro\MacroServiceAwareTrait;
use DateTime;
use Formation\Entity\Db\DemandeExterne;
use Formation\Entity\Db\FormationAbonnement;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\Inscription;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Provider\Role\FormationRoles;
use Formation\Provider\Template\MailTemplates;
use Formation\Service\Url\UrlServiceAwareTrait;
use RuntimeException;
use UnicaenMail\Entity\Db\Mail;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class NotificationService {
    use AgentServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use RoleServiceAwareTrait;
    use UserServiceAwareTrait;
    use MacroServiceAwareTrait;
    use UrlServiceAwareTrait;

    /** RECUPERATION DES MAILS *************************/

    public function getMailsResponsablesFormations() : array
    {
        $role  = $this->getRoleService()->findByRoleId(FormationRoles::GESTIONNAIRE_FORMATION);
        $users = $this->getUserService()->findByRole($role);
        $mails = array_map(function (User $a) { return $a->getEmail(); }, $users);
        return $mails;

    }

    public function getMailsSuperieursByAgent(Agent $agent) : array
    {
        $responsables = $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent);
        $email = array_map(
            function (AgentSuperieur $a) { return $a->getSuperieur()->getEmail();},
            $responsables
        );
        return $email;
    }

    /** GESTION DES INSCRIPTIONS **************************************************************************************/

    public function triggerInscriptionAgent(Agent $agent, FormationInstance $instance) : ?Mail
    {
        if (!$instance->isMailActive()) return null;
        $vars = [
            'agent' => $agent,
            'session' => $instance,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_DEMANDE_AGENT, $vars);

        $mail = $this->getMailService()->sendMail($this->getMailsSuperieursByAgent($agent), $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        if ($mail) {
            $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
            $this->getMailService()->update($mail);
        }
        return $mail;
    }

    public function triggerResponsableValidation(Inscription $inscription) : ?Mail
    {
        $instance = $inscription->getSession();
        if (!$instance->isMailActive()) return null;

        $agent = $inscription->getAgent();

        $email = $this->getParametreService()->getParametreByCode('FORMATION','MAIL_DRH_FORMATION')->getValeur();
        $email .= ",";
        $email .= $agent->getEmail();

        $urlService = $this->getUrlService();
        $urlService->setVariables(['instance' => $inscription->getSession(),]);

        $vars = [
            'agent' => $agent,
            'session' => $instance,
            'inscription' => $inscription,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_RESPONSABLE_VALIDATION, $vars);

        $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerResponsableRefus(Inscription $inscription) : ?Mail
    {
        $instance = $inscription->getSession();
        if (!$instance->isMailActive()) return null;
        $agent = $inscription->getAgent();

        $email = $this->getParametreService()->getParametreByCode('FORMATION','MAIL_DRH_FORMATION')->getValeur();
        $email .= ",";
        $email .= $agent->getEmail();

        $vars = [
            'agent' => $agent,
            'session' => $instance,
            'inscription' => $inscription,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_RESPONSABLE_REFUS, $vars);

        $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerDrhValidation(Inscription $inscription) : ?Mail
    {
        $session = $inscription->getSession();
        if (!$session->isMailActive()) return null;
        $individu = $inscription->getIndividu();

        $vars = [
            'individu' => $individu,
            'session' => $session,
            'inscription' => $inscription,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_DRH_VALIDATION, $vars);

        $mail = $this->getMailService()->sendMail($individu->getEmail(), $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$session->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerPrevention(Inscription $inscription) : ?Mail
    {
        $session = $inscription->getSession();
        if (!$session->isMailActive()) return null;
        $individu = $inscription->getIndividu();

        $email = $this->getParametreService()->getParametreByCode(FormationParametres::TYPE,FormationParametres::MAIL_PREVENTION_FORMATION)->getValeur();

        $vars = [
            'individu' => $individu,
            'session' => $session,
            'inscription' => $inscription,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_PREVENTION, $vars);

        $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$session->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }



    public function triggerDrhRefus(Inscription $inscription) : ?Mail
    {
        $session = $inscription->getSession();
        if (!$session->isMailActive()) return null;
        $individu = $inscription->getIndividu();


        $vars = [
            'individu' => $individu,
            'session' => $session,
            'inscription' => $inscription,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_DRH_REFUS, $vars);

        $mail = $this->getMailService()->sendMail($individu->getEmail(), $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$session->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }


    public function triggerListePrincipale(Inscription $inscrit) : ?Mail
    {
        $instance = $inscrit->getSession();
        if (!$instance->isMailActive()) return null;
        $individu = $inscrit->getIndividu();

        $vars = [
            'agent' => $individu,
            'session' => $instance,
            'inscription' => $inscrit,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::SESSION_LISTE_PRINCIPALE, $vars);
        $mail = $this->getMailService()->sendMail($inscrit->getIndividu()->getEmail(), $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerListeComplementaire(Inscription $inscrit) : ?Mail
    {
        $instance = $inscrit->getSession();
        if (!$instance->isMailActive()) return null;
        $individu = $inscrit->getIndividu();

        $vars = [
            'agent' => $individu,
            'session' => $instance,
            'inscription' => $inscrit,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::SESSION_LISTE_COMPLEMENTAIRE, $vars);
        $mail = $this->getMailService()->sendMail($individu->getEmail(), $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerRetraitListe(Inscription $inscrit) : ?Mail
    {
        $instance = $inscrit->getSession();
        if (!$instance->isMailActive()) return null;
        $agent = $inscrit->getAgent();

        $vars = [
            'agent' => $agent,
            'session' => $instance,
            'inscription' => $inscrit,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::SESSION_LISTE_COMPLEMENTAIRE, $vars);
        $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerConvocation(Inscription $inscrit) : ?Mail
    {
        $instance = $inscrit->getSession();
        if ($instance === null) throw new RuntimeException("Aucune session d'identifié pour cette inscription");
        if (!$instance->isMailActive()) return null;

        $agent = $inscrit->getIndividu();
        if ($agent === null) throw new RuntimeException("Aucune personne d'identifié·e pour cette inscription");

        $vars = [
            'session' => $instance,
            'agent' => $agent,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService()
        ];

        $copie = [];
        $avecCopieSuperieur = $this->getParametreService()->getValeurForParametre(FormationParametres::TYPE, FormationParametres::CONVOCATION_SUPERIEUR_COPIE);
        if ($avecCopieSuperieur && $inscrit->getAgent() !== null) {
            $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent);
            foreach ($superieurs as $superieur) $copie[] = $superieur->getSuperieur()->getEmail();
        }

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::SESSION_CONVOCATION, $vars);
        $mail = $this->getMailService()->sendMail($agent->getEmail(), $rendu->getSujet(), $rendu->getCorps(), 'Formation', null, $copie);
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerDemandeRetour(Inscription $inscrit) : ?Mail
    {
        $instance = $inscrit->getSession();
        if ($instance === null) throw new RuntimeException("Aucune session d'identifié pour cette inscription");
        if (!$instance->isMailActive()) return null;

        $agent = $inscrit->getIndividu();
        if ($agent === null) throw new RuntimeException("Aucune personne d'identifié·e pour cette inscription");


        $vars = [
            'session' => $instance,
            'agent' => $inscrit->getAgent(),
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
            'inscription' => $inscrit,
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::SESSION_DEMANDE_RETOUR, $vars);
        $mail = $this->getMailService()->sendMail($inscrit->getIndividu()->getEmail(), $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerSessionAnnulee(Inscription $inscrit) : ?Mail
    {
        $instance = $inscrit->getSession();
        if ($instance === null) throw new RuntimeException("Aucune session d'identifié pour cette inscription");
        if (!$instance->isMailActive()) return null;

        $agent =  $inscrit->getAgent();
        if ($agent === null) throw new RuntimeException("Aucune personne d'identifié·e pour cette inscription");

        $vars = [
            'session' => $instance,
            'agent' => $inscrit->getAgent(),
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService()
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::SESSION_ANNULEE, $vars);
        $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerLienPourEmargement(FormationInstance $instance) : ?Mail
    {
        if (!$instance->isMailActive()) return null;
        $mails = [];
        foreach ($instance->getFormateurs() as $formateur) {
            $mails[] = $formateur->getEmail();
        }

        $urlService = $this->getUrlService()->setVariables(['instance' => $instance]);
        $vars = ['session' => $instance, 'instance' => $instance, 'UrlService' => $urlService];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::SESSION_EMARGEMENT, $vars);
        $mail = $this->getMailService()->sendMail(implode(",", $mails), $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    /** Mails de rappel ***********************************************************************************************/

    public function triggerRappelAgentAvantFormation(FormationInstance $instance) : ?array
    {
        $vars = [
            'session' => $instance,
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_RAPPEL_AVANT_FORMATION, $vars);

        $liste = $instance->getListePrincipale();
        $mails = [];
        foreach ($liste as $inscrit) {
            $agent = $inscrit->getIndividu();
            $mail = $this->getMailService()->sendMail($agent->getEmail(), $rendu->getSujet(), $rendu->getCorps(), 'Formation');
            $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag(), $agent->generateTag() ]);
            $this->getMailService()->update($mail);
            $mails[] = $mail;
        }
        return $mails;
    }

    /**
     * @param FormationInstance[] $instances
     * @return Mail|null
     */
    public function triggerNotificationFormationsOuvertes(array $instances) : ?Mail
    {
        if (! empty($instances)) {
            $texte = "<ul>";
            foreach ($instances as $instance) {
                $texte .= "<li>" . $instance->getInstanceLibelle() . " : " . $instance->getPeriode() . "</li>";
            }
            $texte .= "</ul>";

            $email = $this->getParametreService()->getParametreByCode(FormationParametres::TYPE,FormationParametres::MAIL_PERSONNEL)->getValeur();
            $vars = [
                'UrlService' => $this->getUrlService(),
            ];
            $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_NOUVELLES_FORMATIONS, $vars);
            $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), str_replace("###A REMPLACER###", $texte, $rendu->getCorps()), 'Formation');
            $mail->setMotsClefs(["NOTIFICATION_FORMATION_" . (new DateTime())->format('d/m/Y')]);
            $this->getMailService()->update($mail);
            return $mail;
        }

        return null;
    }

    /** ABONNEMENTS ***************************************************************************************************/

    public function triggerNouvelleSession(FormationInstance $instance, FormationAbonnement $abonnement) : Mail
    {
        $vars = [
            'formation' => $instance->getFormation(),
            'session' => $instance,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_OUVERTE, $vars);

        $email = $abonnement->getAgent()->getEmail();
        $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    /**
     * @param FormationInstance[] $closes
     * @return Mail|null
     */
    public function triggerNotifierInscriptionClotureAutomatique(array $closes) : ?Mail
    {
        if (!empty($closes)) {
            $texte = "<ul>";
            foreach ($closes as $close) {
                $texte .= "<li>" . $close->getInstanceLibelle() . " - " . $close->getInstanceCode() . "</li>";
            }
            $texte .= "</ul>";

            $email = $this->getMailsResponsablesFormations();
            $vars = [
                'UrlService' => $this->getUrlService(),
            ];
            $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_CLOTURE_AUTOMATIQUE, $vars);
            $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), str_replace("###A REMPLACER###", $texte, $rendu->getCorps()), 'Formation');
            $mail->setMotsClefs([$rendu->getTemplate()->generateTag()]);
            $this->getMailService()->update($mail);
            return $mail;
        }

        return null;
    }

    public function triggerNotifierConvocationAutomatique(array $convocations): ?Mail
    {
        if (!empty($convocations)) {
            $texte = "<ul>";
            foreach ($convocations as $convocation) {
                $texte .= "<li>" . $convocation->getInstanceLibelle() . " - " . $convocation->getInstanceCode() . "</li>";
            }
            $texte .= "</ul>";

            $email = $this->getMailsResponsablesFormations();
            $vars = [
                'UrlService' => $this->getUrlService(),
            ];
            $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_CONVOCATION_AUTOMATIQUE, $vars);
            $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), str_replace("###A REMPLACER###", $texte, $rendu->getCorps()), 'Formation');
            $mail->setMotsClefs([$rendu->getTemplate()->generateTag()]);
            $this->getMailService()->update($mail);
            return $mail;
        }

        return null;
    }

    // NOTIFICATION LIEE AUX DEMANDES DE FORMATION EXTERNE /////////////////////////////////////////////////////////////

    public function triggerValidationAgent(DemandeExterne $demande) : ?Mail
    {
        $agent = $demande->getAgent();

        $vars = [
            'agent' => $demande->getAgent(),
            'demande' => $demande,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::DEMANDE_EXTERNE_VALIDATION_AGENT, $vars);
        $mail = $this->getMailService()->sendMail($this->getMailsSuperieursByAgent($agent), $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$rendu->getTemplate()->generateTag(), $demande->generateTag()]);
        $this->getMailService()->update($mail);
        return $mail;
    }

    public function triggerValidationResponsableAgent(DemandeExterne $demande) : ?Mail
    {
        $agent = $demande->getAgent();
        $email = [ $agent->getEmail() ];

        $vars = [
            'agent' => $demande->getAgent(),
            'demande' => $demande,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::DEMANDE_EXTERNE_VALIDATION_RESP_AGENT, $vars);
        $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$rendu->getTemplate()->generateTag(), $demande->generateTag()]);
        $this->getMailService()->update($mail);
        return $mail;
    }

    public function triggerValidationResponsableDrh(DemandeExterne $demande) : ?Mail
    {
        $email = $this->getMailsResponsablesFormations();

        $vars = [
            'agent' => $demande->getAgent(),
            'demande' => $demande,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::DEMANDE_EXTERNE_VALIDATION_RESP_DRH, $vars);
        $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$rendu->getTemplate()->generateTag(), $demande->generateTag()]);
        $this->getMailService()->update($mail);
        return $mail;
    }

    public function triggerValidationDrh(DemandeExterne $demande) : ?Mail
    {
        $agent = $demande->getAgent();

        $vars = [
            'agent' => $demande->getAgent(),
            'demande' => $demande,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::DEMANDE_EXTERNE_VALIDATION_DRH, $vars);
        $mail = $this->getMailService()->sendMail($this->getMailsSuperieursByAgent($agent), $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$rendu->getTemplate()->generateTag(), $demande->generateTag()]);
        $this->getMailService()->update($mail);
        return $mail;
    }

    public function triggerRefus(DemandeExterne $demande) : ?Mail
    {
        $agent = $demande->getAgent();

        $vars = [
            'agent' => $demande->getAgent(),
            'demande' => $demande,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::DEMANDE_EXTERNE_VALIDATION_REFUS, $vars);
        $mail = $this->getMailService()->sendMail($this->getMailsSuperieursByAgent($agent), $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$rendu->getTemplate()->generateTag(), $demande->generateTag()]);
        $this->getMailService()->update($mail);
        return $mail;
    }


    public function triggerValidationComplete(DemandeExterne $demande) : ?Mail
    {
        $email = $this->getMailsResponsablesFormations();

        $vars = [
            'agent' => $demande->getAgent(),
            'demande' => $demande,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::DEMANDE_EXTERNE_VALIDATION_COMPLETE, $vars);
        $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), $rendu->getCorps(), 'Formation');
        $mail->setMotsClefs([$rendu->getTemplate()->generateTag(), $demande->generateTag()]);
        $this->getMailService()->update($mail);
        return $mail;
    }
}