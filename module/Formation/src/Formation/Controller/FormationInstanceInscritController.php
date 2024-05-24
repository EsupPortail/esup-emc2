<?php

namespace Formation\Controller;

use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\DemandeExterne;
use Formation\Form\Inscription\InscriptionFormAwareTrait;
use Formation\Provider\Etat\DemandeExterneEtats;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Provider\Template\TextTemplates;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\Inscription\InscriptionServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Formation\Service\StagiaireExterne\StagiaireExterneServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */
class FormationInstanceInscritController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use DemandeExterneServiceAwareTrait;
    use EtatInstanceServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use InscriptionServiceAwareTrait;
    use MailServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use StagiaireExterneServiceAwareTrait;
    use UserServiceAwareTrait;

    use InscriptionFormAwareTrait;
    use SelectionAgentFormAwareTrait;


    public function inscriptionInterneAction(): ViewModel
    {
        $instances = $this->getFormationInstanceService()->getFormationsInstancesByEtat(SessionEtats::ETAT_INSCRIPTION_OUVERTE);
        $utilisateur = $this->getUserService()->getConnectedUser();

        $agent = $this->getAgentService()->getAgentByUser($utilisateur);
        $inscriptions = $this->getInscriptionService()->getInscriptionByUser($utilisateur);

        return new ViewModel([
            'instances' => $instances,
            'inscriptions' => $inscriptions,
            'agent' => $agent,
//            'stagiaire' => $stagiaire,
        ]);
    }

    public function inscriptionExterneAction(): ViewModel
    {
        $utilisateur = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($utilisateur);


        $demandes = $this->getDemandeExterneService()->getDemandesExternesByAgent($agent);
        $demandes = array_filter($demandes, function (DemandeExterne $d) {
            return $d->estNonHistorise();
        });
        $demandesNonValidees = array_filter($demandes, function (DemandeExterne $d) {
            return $d->isEtatActif(DemandeExterneEtats::ETAT_CREATION_EN_COURS);
        });

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(TextTemplates::STAGE_HORS_PLAN, [], false);

        return new ViewModel([
            'agent' => $agent,
            'demandes' => $demandesNonValidees,
            'rendu' => $rendu,
        ]);
    }

    public function formationsAction(): ViewModel
    {
        $utilisateur = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($utilisateur);
        $stagiaire = null;
        $inscriptions = null;

        if ($agent !== null) $inscriptions = $this->getInscriptionService()->getInscriptionsByAgent($agent);
        else {
            $stagiaire = $this->getStagiaireExterneService()->getStagiaireExterneByUser($utilisateur);
            if ($stagiaire) $inscriptions = $this->getInscriptionService()->getInscriptionbyStagiaire($stagiaire);
        }

        $mail = $this->getParametreService()->getParametreByCode(FormationParametres::TYPE, FormationParametres::MAIL_DRH_FORMATION);
        $enqueteExplication = $this->getRenduService()->generateRenduByTemplateCode(TextTemplates::ENQUETE_EXPLICATION, [], false);


        return new ViewModel([
            'agent' => $agent,
            'stagiaire' => $stagiaire,
            'inscriptions' => $inscriptions,
            'mailcontact' => ($mail) ? $mail->getValeur() : null,
            'enqueteExplication' => $enqueteExplication,
        ]);
    }

    public function inscriptionsAction(): ViewModel
    {
        $utilisateur = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($utilisateur);

        $inscriptions = $this->getInscriptionService()->getInscriptionsByAgent($agent);

        $demandes = $this->getDemandeExterneService()->getDemandesExternesByAgent($agent);
        $demandes = array_filter($demandes,
            function (DemandeExterne $d) {
                return $d->estNonHistorise()
                    && !$d->isEtatActif(DemandeExterneEtats::ETAT_REJETEE)
                    && !$d->isEtatActif(DemandeExterneEtats::ETAT_TERMINEE);
            });
        $demandesValidees = array_filter($demandes, function (DemandeExterne $d) {
            return !$d->isEtatActif(DemandeExterneEtats::ETAT_CREATION_EN_COURS);
        });

        return new ViewModel([
            'agent' => $agent,

            'inscriptions' => $inscriptions,
            'demandes' => $demandesValidees,
        ]);
    }



}