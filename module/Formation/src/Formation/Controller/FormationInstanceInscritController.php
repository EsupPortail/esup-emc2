<?php

namespace Formation\Controller;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\DemandeExterne;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Form\Inscription\InscriptionFormAwareTrait;
use Formation\Provider\Etat\DemandeExterneEtats;
use Formation\Provider\Etat\InscriptionEtats;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
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
    use FormationInstanceInscritServiceAwareTrait;
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

        $inscriptions = $this->getInscriptionService()->getInscriptionByUser($utilisateur);

        return new ViewModel([
            'instances' => $instances,
            'inscriptions' => $inscriptions,
//            'agent' => $agent,
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

        return new ViewModel([
            'agent' => $agent,
            'demandes' => $demandesNonValidees,
        ]);
    }

    public function formationsAction(): ViewModel
    {
        $utilisateur = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($utilisateur);

        $formations = $this->getFormationInstanceInscritService()->getFormationsBySuivies($agent);
        $mail = $this->getParametreService()->getParametreByCode(FormationParametres::TYPE, FormationParametres::MAIL_DRH_FORMATION);

        return new ViewModel([
            'agent' => $agent,
            'formations' => $formations,
            'mailcontact' => ($mail) ? $mail->getValeur() : null,
        ]);
    }

    public function inscriptionsAction(): ViewModel
    {
        $utilisateur = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($utilisateur);

        $inscriptions = $this->getFormationInstanceInscritService()->getFormationsByInscrit($agent);

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

    public function inscriptionAction(): ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $inscription = new FormationInstanceInscrit();
        $inscription->setInstance($instance);
        $inscription->setAgent($agent);

        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/inscription', ['formation-instance' => $instance->getId(), 'agent' => $agent->getId()], [], true));
        $form->bind($inscription);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $justification = (isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) !== '') ? trim($data['HasDescription']['description']) : null;
                if ($justification === null) {
                    $this->flashMessenger()->addErrorMessage("<span class='text-danger'><strong> Échec de l'inscription  </strong></span> <br/> Veuillez motivier votre demande d'inscription!");
                } else {
                    $inscription->setJustificationAgent($justification);
                    $inscription->setSource(HasSourceInterface::SOURCE_EMC2);
                    $this->getFormationInstanceInscritService()->create($inscription);
                    $this->getEtatInstanceService()->setEtatActif($inscription, InscriptionEtats::ETAT_DEMANDE);
                    $this->getFormationInstanceInscritService()->update($inscription);
                    $this->flashMessenger()->addSuccessMessage("Demande d'inscription faite.");
                    $this->getNotificationService()->triggerInscriptionAgent($agent, $instance);
                }
            }
        }

        return new ViewModel([
            'title' => "Inscription à la formation " . $instance->getInstanceLibelle() . " du " . $instance->getPeriode(),
            'inscription' => $inscription,
            'form' => $form,
        ]);
    }

    public function desinscriptionAction(): ViewModel
    {
        $inscription = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $instance = $inscription->getInstance();
        $agent = $inscription->getAgent();


        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/desinscription', ['inscrit' => $inscription->getId(), 'agent' => $agent->getId()], [], true));
        $form->bind($inscription);

        $break = false;
        if ($agent->getUtilisateur() !== $this->getUserService()->getConnectedUser()) {
            $this->flashMessenger()->addErrorMessage("L'utilisateur connecté ne correspond à l'agent en train de se déinscrire !");
            $break = true;
        }

        if (!$break) {
            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $form->setData($data);
                if ($form->isValid()) {
                    $justification = (isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) !== '') ? trim($data['HasDescription']['description']) : null;
                    if ($justification === null) {
                        $this->flashMessenger()->addErrorMessage("<span class='text-danger'><strong> Échec de la désinscription  </strong></span> <br/> Veuillez justifier votre demande de désinscription !");
                    } else {
                        $inscription->setJustificationRefus($justification);
                        $this->getEtatInstanceService()->setEtatActif($inscription, InscriptionEtats::ETAT_REFUSER);
                        $this->getFormationInstanceInscritService()->historise($inscription);
                        $this->flashMessenger()->addSuccessMessage("Désinscription faite.");
                        //todo trigger reclassement
                    }
                }
            }
        }

        $vm = new ViewModel([
            'title' => "Désinscription à la formation " . $instance->getInstanceLibelle() . " du " . $instance->getPeriode(),
            'inscription' => $inscription,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }


}