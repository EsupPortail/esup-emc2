<?php

namespace Formation\Service\Notification;

use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Formation\Entity\Db\FormationAbonnement;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Provider\Roles;
use Formation\Provider\Template\MailTemplates;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\Url\UrlServiceAwareTrait;
use UnicaenMail\Entity\Db\Mail;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class NotificationService {
    use AgentServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use RoleServiceAwareTrait;
    use UserServiceAwareTrait;
    use UrlServiceAwareTrait;

    /** RECUPERATION DES MAILS *************************/

    public function getMailsResponsablesFormations() : array
    {
        $role  = $this->getRoleService()->findByRoleId(Roles::GESTIONNAIRE_FORMATION);
        $users = $this->getUserService()->findByRole($role);
        $mails = array_map(function (User $a) { return $a->getEmail(); }, $users);
        return $mails;

    }
    /** GESTION DES INSCRIPTIONS **************************************************************************************/

    public function triggerInscriptionAgent(Agent $agent, FormationInstance $instance) : Mail
    {
        $vars = [
            'agent' => $agent,
            'instance' => $instance,
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_DEMANDE_AGENT, $vars);

        $superieurs = $this->getAgentService()->computeSuperieures($agent);
        $emails = [];
        /** @var Agent $superieur */
        foreach ($superieurs as $superieur) {
            if ($superieur->getEmail()) $emails[] = $superieur->getEmail();
        }
        $mail = $this->getMailService()->sendMail($emails, $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerResponsableValidation(FormationInstanceInscrit $inscription) : Mail
    {
        $instance = $inscription->getInstance();
        $agent = $inscription->getAgent();

            $email = $this->getParametreService()->getParametreByCode('FORMATION','MAIL_DRH_FORMATION')->getValeur();
        $email .= ",";
        $email .= $agent->getEmail();

        $urlService = $this->getUrlService();
        $urlService->setVariables(['instance' => $inscription->getInstance(),]);

        $vars = [
            'agent' => $inscription->getAgent(),
            'instance' => $inscription->getInstance(),
            'UrlService' => $urlService,
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_RESPONSABLE_VALIDATION, $vars);

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
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_RESPONSABLE_REFUS, $vars);

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
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_DRH_VALIDATION, $vars);

        $mail = $this->getMailService()->sendMail($agent->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerDrhRefus(FormationInstanceInscrit $inscription) : Mail
    {
        $instance = $inscription->getInstance();
        $agent = $inscription->getAgent();

        $vars = [
            'agent' => $agent,
            'instance' => $instance,
            'inscrit' => $inscription
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_DRH_REFUS, $vars);

        $mail = $this->getMailService()->sendMail($agent->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }


    public function triggerListePrincipale(FormationInstanceInscrit $inscrit) : Mail
    {
        $instance = $inscrit->getInstance();

        $vars = [
            'agent' => $inscrit->getAgent(),
            'instance' => $instance,
            'UrlService' => $this->getUrlService()
        ];

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::SESSION_LISTE_PRINCIPALE, $vars);
        $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerListeComplementaire(FormationInstanceInscrit $inscrit) : Mail
    {
        $instance = $inscrit->getInstance();

        $vars = [
            'agent' => $inscrit->getAgent(),
            'instance' => $instance,
            'UrlService' => $this->getUrlService()
        ];

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::SESSION_LISTE_COMPLEMENTAIRE, $vars);
        $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerConvocation(FormationInstanceInscrit $inscrit) : Mail
    {
        $instance = $inscrit->getInstance();

        $vars = [
            'instance' => $instance,
            'agent' => $inscrit->getAgent(),
            'UrlService' => $this->getUrlService()
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::SESSION_CONVOCATION, $vars);
        $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerDemandeRetour(FormationInstanceInscrit $inscrit) : Mail
    {
        $instance = $inscrit->getInstance();

        $vars = [
            'instance' => $instance,
            'agent' => $inscrit->getAgent(),
            'UrlService' => $this->getUrlService()
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::SESSION_DEMANDE_RETOUR, $vars);
        $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);

        return $mail;
    }

    public function triggerLienPourEmargement(FormationInstance $instance) : Mail
    {
        $mails = [];
        foreach ($instance->getFormateurs() as $formateur) {
            $mails[] = $formateur->getEmail();
        }

        $urlService = $this->getUrlService()->setVariables(['instance' => $instance]);
        $vars = ['instance' => $instance, 'UrlService' => $urlService];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::SESSION_EMARGEMENT, $vars);
        $mail = $this->getMailService()->sendMail(implode(",", $mails), $rendu->getSujet(), $rendu->getCorps());
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
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_RAPPEL_AVANT_FORMATION, $vars);

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

            $email = $this->getParametreService()->getParametreByCode('FORMATION','MAIL_PERSONNEL')->getValeur();
            $vars = [
                'UrlService' => $this->getUrlService(),
            ];
            $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_NOUVELLES_FORMATIONS, $vars);
            $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), str_replace("###A REMPLACER###", $texte, $rendu->getCorps()));
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
            'instance' => $instance,
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::FORMATION_INSCRIPTION_OUVERTE, $vars);

        $email = $abonnement->getAgent()->getEmail();
        $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), $rendu->getCorps());
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
            $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), str_replace("###A REMPLACER###", $texte, $rendu->getCorps()));
            $mail->setMotsClefs([$rendu->getTemplate()->generateTag()]);
            $this->getMailService()->update($mail);
            return $mail;
        }

        return null;
    }

}