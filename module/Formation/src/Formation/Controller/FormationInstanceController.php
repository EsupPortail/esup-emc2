<?php

namespace Formation\Controller;

use Formation\Form\FormationInstance\FormationInstanceFormAwareTrait;
use Formation\Provider\Etat\SessionEtats;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Formation\Service\Presence\PresenceServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\EtatCategorie\EtatCategorieServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */
class FormationInstanceController extends AbstractActionController
{
    use EtatCategorieServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use MailServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use PresenceServiceAwareTrait;
    use FormationInstanceFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $instances = $this->getFormationInstanceService()->getFormationInstanceEnCours();

        return new ViewModel([
            'instances' => $instances,
        ]);
    }

    /** NB : par defaut les instances de formation sont toutes en autoinscription **************************************/

    public function ajouterAction(): Response
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $instance = $this->getFormationInstanceService()->createNouvelleInstance($formation);

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function ajouterAvecFormulaireAction(): ViewModel
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $formation_ = $data['formation']['id'];
            $formation = $this->getFormationService()->getFormation($formation_);

            if ($formation) {
                $instance = $this->getFormationInstanceService()->createNouvelleInstance($formation);
                $this->flashMessenger()->addSuccessMessage("La session #" . $instance->getId() . " vient d'être créée.");
            } else {
                $this->flashMessenger()->addErrorMessage("La formation sélectionnée est incorrecte.");
            }
        }

        return new ViewModel([
            'title' => "Ouverture d'une nouvelle session de formation",
        ]);

    }

    public function afficherAction(): ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $mails = $this->getMailService()->getMailsByMotClef($instance->generateTag());

        $presences = $this->getPresenceService()->getPresenceByInstance($instance);

        $dictionnaire = [];
        foreach ($presences as $presence) {
            $dictionnaire[$presence->getJournee()->getId()][$presence->getInscription()->getId()] = $presence;
        }

        return new ViewModel([
            'instance' => $instance,
            'mode' => "affichage",
            'presences' => $dictionnaire,
            'mails' => $mails,
        ]);
    }

    public function modifierAction(): ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        return new ViewModel([
            'instance' => $instance,
            'mode' => "modification",
        ]);
    }

    public function historiserAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->historise($instance);

        return $this->redirect()->toRoute('formation/editer', ['formation' => $instance->getFormation()->getId()], [], true);
    }

    public function restaurerAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->restore($instance);

        return $this->redirect()->toRoute('formation/editer', ['formation' => $instance->getFormation()->getId()], [], true);
    }

    public function supprimerAction(): ViewModel
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
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'une instance de formation",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer', ["formation-instance" => $instance->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function modifierInformationsAction(): ViewModel
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

    public function ouvrirInscriptionAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->ouvrirInscription($instance);

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function fermerInscriptionAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->fermerInscription($instance);


        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function envoyerConvocationAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->envoyerConvocation($instance);
        //$this->getFormationInstanceService()->envoyerEmargement($instance); //todo définir ce que l'on fait pour les formateurs
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function demanderRetourAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->demanderRetour($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function cloturerAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->cloturer($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function annulerAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->annuler($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function reouvrirAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->reouvrir($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function changerEtatAction(): ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $etat = $this->getEtatTypeService()->getEtatType($data['etat']);

            switch ($etat->getCode()) {
                case SessionEtats::ETAT_CREATION_EN_COURS :
                    $this->getFormationInstanceService()->recreation($instance);
                    exit();
                case SessionEtats::ETAT_INSCRIPTION_OUVERTE :
                    $this->getFormationInstanceService()->ouvrirInscription($instance);
                    exit();
                case SessionEtats::ETAT_INSCRIPTION_FERMEE :
                    $this->getFormationInstanceService()->fermerInscription($instance);
                    exit();
                case SessionEtats::ETAT_FORMATION_CONVOCATION :
                    $this->getFormationInstanceService()->envoyerConvocation($instance);
                    exit();
                case SessionEtats::ETAT_ATTENTE_RETOURS :
                    $this->getFormationInstanceService()->demanderRetour($instance);
                    exit();
                case SessionEtats::ETAT_CLOTURE_INSTANCE :
                    $this->getFormationInstanceService()->cloturer($instance);
                    exit();
                default :
            }

            exit();
        }

        return new ViewModel([
            'title' => "Changer l'état de la session de formation",
            'etats' => $this->getEtatTypeService()->getEtatsTypesByCategorieCode(SessionEtats::TYPE),
            'instance' => $instance,
        ]);
    }

    public function rechercherAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $sessions = $this->getFormationInstanceService()->getSessionByTerm($term);
            $result = $this->getFormationInstanceService()->formatSessionJSON($sessions);
            return new JsonModel($result);
        }
        exit;
    }
}