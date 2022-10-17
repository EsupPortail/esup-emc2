<?php

namespace Formation\Controller;

use Formation\Service\Notification\NotificationServiceAwareTrait;
use Formation\Service\Presence\PresenceAwareTrait;
use UnicaenAutoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use DateInterval;
use DateTime;
use Formation\Form\FormationInstance\FormationInstanceFormAwareTrait;
use Formation\Service\Evenement\RappelAgentAvantFormationServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */

class FormationInstanceController extends AbstractActionController
{
    use EtatServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;
    use MailServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use PresenceAwareTrait;
    use FormationInstanceFormAwareTrait;
    use RappelAgentAvantFormationServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $instances = $this->getFormationInstanceService()->getFormationInstanceEnCours();

        return new ViewModel([
            'instances' => $instances,
        ]);
    }

    /** NB: par defaut les instances de formation sont toutes en autoinscription **************************************/

    public function ajouterAction() : Response
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $instance = $this->getFormationInstanceService()->createNouvelleInstance($formation);

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function ajouterAvecFormulaireAction() : ViewModel
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $formation_ = $data['formation']['id'];
            $formation = $this->getFormationService()->getFormation($formation_);

            if ($formation) {
                $instance = $this->getFormationInstanceService()->createNouvelleInstance($formation);
                $this->flashMessenger()->addSuccessMessage("La session #". $instance->getId()." vient d'être créée.");
            } else {
                $this->flashMessenger()->addErrorMessage("La formation sélectionnée est incorrecte.");
            }
        }

        return new ViewModel([
            'title' => "Ouverture d'une nouvelle session de formation",
        ]);

    }

    public function afficherAction() : ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $mails = $this->getMailService()->getMailsByMotClef($instance->generateTag());

        $presences = $this->getPresenceService()->getPresenceByInstance($instance);

        $dictionnaire = [];
        foreach ($presences as $presence) {
            $dictionnaire[$presence->getJournee()->getId()][$presence->getInscrit()->getId()] = $presence;
        }

        return new ViewModel([
            'instance' => $instance,
            'mode' => "affichage",
            'presences' => $dictionnaire,
            'mails' => $mails,
        ]);
    }

    public function modifierAction() : ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        return new ViewModel([
            'instance' => $instance,
            'mode' => "modification",
        ]);
    }

    public function historiserAction() : Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->historise($instance);

        return $this->redirect()->toRoute('formation/editer', ['formation' => $instance->getFormation()->getId()], [], true);
    }

    public function restaurerAction() : Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->restore($instance);

        return $this->redirect()->toRoute('formation/editer', ['formation' => $instance->getFormation()->getId()], [], true);
    }

    public function supprimerAction() : ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationInstanceService()->delete($instance);
            exit();
        }

        $vm = new ViewModel();
        if ($instance !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'une instance de formation",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer', ["formation-instance" => $instance->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function modifierInformationsAction() : ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $form = $this->getFormationInstanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/modifier-informations', ['formation-instance' => $instance->getId()], [], true));
        $form->bind($instance);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceService()->update($instance);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification des informations de la session",
            'form' => $form,
        ]);
        return $vm;
    }

    /** Question suite à la formation */

    public function renseignerQuestionnaireAction() : ViewModel
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        if ($inscrit->getQuestionnaire() === null) {
            $questionnaire = $this->getFormulaireInstanceService()->createInstance('QUESTIONNAIRE_FORMATION');
            $inscrit->setQuestionnaire($questionnaire);
            $this->getFormationInstanceInscritService()->update($inscrit);
        }

        return new ViewModel([
            'inscrit' => $inscrit,
        ]);
    }

    public function ouvrirInscriptionAction() : Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->ouvrirInscription($instance);

        //notification abonnement
        $abonnements = $instance->getFormation()->getAbonnements();
        foreach ($abonnements as $abonnement) {
            if ($abonnement->estNonHistorise()) $this->getNotificationService()->triggerNouvelleSession($instance, $abonnement);
        }

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function fermerInscriptionAction() : Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->fermerInscription($instance);

        $dateRappel = DateTime::createFromFormat('d/m/Y H:i', $instance->getDebut() . " 08:00");
        $dateRappel->sub(new DateInterval('P4D'));
        $this->getRappelAgentAvantFormationService()->creer($instance, $dateRappel);

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function envoyerConvocationAction() : Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->envoyerConvocation($instance);
        //$this->getFormationInstanceService()->envoyerEmargement($instance); //todo définir ce que l'on fait pour les formateurs
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function demanderRetourAction() : Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->demanderRetour($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function cloturerAction() : Response
    {
          $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
          $this->getFormationInstanceService()->cloturer($instance);
          return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function annulerAction() : Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->annuler($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function reouvrirAction() : Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->reouvrir($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }
}