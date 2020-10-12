<?php

namespace Application\Controller;

use Application\Entity\Db\FormationInstance;
use Application\Entity\Db\FormationInstanceFormateur;
use Application\Entity\Db\FormationInstanceFrais;
use Application\Entity\Db\FormationInstanceInscrit;
use Application\Entity\Db\FormationInstanceJournee;
use Application\Entity\Db\FormationInstancePresence;
use Application\Form\FormationInstance\FormationInstanceFormAwareTrait;
use Application\Form\FormationInstanceFormateur\FormationInstanceFormateurFormAwareTrait;
use Application\Form\FormationInstanceFrais\FormationInstanceFraisFormAwareTrait;
use Application\Form\FormationJournee\FormationJourneeFormAwareTrait;
use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Service\Export\Formation\Emargement\EmargementPdfExporter;
use Application\Service\Formation\FormationServiceAwareTrait;
use Application\Service\FormationInstance\FormationInstanceFormateurServiceAwareTrait;
use Application\Service\FormationInstance\FormationInstanceFraisServiceAwareTrait;
use Application\Service\FormationInstance\FormationInstanceInscritServiceAwareTrait;
use Application\Service\FormationInstance\FormationInstanceJourneeServiceAwareTrait;
use Application\Service\FormationInstance\FormationInstancePresenceAwareTrait;
use Application\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Mpdf\MpdfException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenDocument\Service\Exporter\ExporterServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */

class FormationInstanceController extends AbstractActionController {
    use FormationServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use FormationInstanceJourneeServiceAwareTrait;
    use FormationInstanceFormateurServiceAwareTrait;
    use FormationInstanceFraisServiceAwareTrait;
    use FormationInstancePresenceAwareTrait;
    use FormationInstanceFormAwareTrait;
    use FormationJourneeFormAwareTrait;
    use FormationInstanceFormateurFormAwareTrait;
    use FormationInstanceFraisFormAwareTrait;
    use SelectionAgentFormAwareTrait;
    use ExporterServiceAwareTrait;

    private $renderer;

    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    public function ajouterAction() {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $instance = new FormationInstance();
        $instance->setNbPlacePrincipale(0);
        $instance->setNbPlaceComplementaire(0);
        $instance->setFormation($formation);

        $this->getFormationInstanceService()->create($instance);

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function afficherAction() {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        //todo une seule vue parametrée par les privilèges || mode
        return new ViewModel([
            'instance' => $instance,
            'mode' => "affichage",
        ]);
    }

    public function modifierAction() {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        //todo une seule vue parametrée par les privilèges || mode
        return new ViewModel([
            'instance' => $instance,
            'mode' => "modification",
        ]);
    }

    public function historiserAction() {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->historise($instance);

        return $this->redirect()->toRoute('formation/editer', ['formation' => $instance->getFormation()->getId()], [], true);
    }

    public function restaurerAction() {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->restore($instance);

        return $this->redirect()->toRoute('formation/editer', ['formation' => $instance->getFormation()->getId()], [], true);
    }

    public function supprimerAction() {
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

    /** INSTANCE *******************************************************************************************/

    public function modifierInformationsAction()
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
            'title' => "Modification des informations de l'instance",
            'form' => $form,
        ]);
        return $vm;
    }

    /** FORMATEUR ******************************************************************************************/

    public function ajouterFormateurAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $formateur = new FormationInstanceFormateur();
        $formateur->setInstance($instance);

        $form = $this->getFormationInstanceFormateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/ajouter-formateur', ['formation-instance' => $instance->getId()], [], true));
        $form->bind($formateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceFormateurService()->create($formateur);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un formateur de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierFormateurAction() {
        $formateur = $this->getFormationInstanceFormateurService()->getRequestedFormationInstanceFormateur($this);

        $form = $this->getFormationInstanceFormateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/modifier-formateur', ['formateur' => $formateur->getId()], [], true));
        $form->bind($formateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceFormateurService()->update($formateur);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un formateur de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserFormateurAction()
    {
        $formateur = $this->getFormationInstanceFormateurService()->getRequestedFormationInstanceFormateur($this);
        $this->getFormationInstanceFormateurService()->historise($formateur);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $formateur->getInstance()->getId()], [], true);
    }

    public function restaurerFormateurAction()
    {
        $formateur = $this->getFormationInstanceFormateurService()->getRequestedFormationInstanceFormateur($this);
        $this->getFormationInstanceFormateurService()->restore($formateur);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $formateur->getInstance()->getId()], [], true);
    }

    public function supprimerFormateurAction()
    {
        $formateur = $this->getFormationInstanceFormateurService()->getRequestedFormationInstanceFormateur($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationInstanceFormateurService()->delete($formateur);
            exit();
        }

        $vm = new ViewModel();
        if ($formateur !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du formateur de formation du [" . $formateur->getPrenom() . " ". $formateur->getNom() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer-formateur', ["formateur" => $formateur->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** JOURNEE ********************************************************************************************/

    public function ajouterJourneeAction() {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $journee = new FormationInstanceJournee();
        $journee->setInstance($instance);

        $form = $this->getFormationJourneeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/ajouter-journee', ['formation-instance' => $instance->getId()], [], true));
        $form->bind($journee);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceJourneeService()->create($journee);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
           'title' => "Ajout d'une journée de formation",
           'form' => $form,
        ]);
        return $vm;
    }

    public function modifierJourneeAction() {
        $journee = $this->getFormationInstanceJourneeService()->getRequestedFormationInstanceJournee($this);

        $form = $this->getFormationJourneeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/modifier-journee', ['journee' => $journee->getId()], [], true));
        $form->bind($journee);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceJourneeService()->update($journee);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une journée de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserJourneeAction()
    {
        $journee = $this->getFormationInstanceJourneeService()->getRequestedFormationInstanceJournee($this);
        $this->getFormationInstanceJourneeService()->historise($journee);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $journee->getInstance()->getId()], [], true);
    }

    public function restaurerJourneeAction()
    {
        $journee = $this->getFormationInstanceJourneeService()->getRequestedFormationInstanceJournee($this);
        $this->getFormationInstanceJourneeService()->restore($journee);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $journee->getInstance()->getId()], [], true);
    }

    public function supprimerJourneeAction()
    {
        $journee = $this->getFormationInstanceJourneeService()->getRequestedFormationInstanceJournee($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationInstanceJourneeService()->delete($journee);
            exit();
        }

        $vm = new ViewModel();
        if ($journee !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la journée de formation du [" . $journee->getJour() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer-journee', ["journee" => $journee->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function exportEmargementAction()
    {
        $journee = $this->getFormationInstanceJourneeService()->getRequestedFormationInstanceJournee($this);

        $exporter = new EmargementPdfExporter($this->renderer, 'A4');
        $exporter->setVars([
            'journee' => $journee,
        ]);

        //$filemane = "PrEECoG_Emargement_" . /** . $this->getDateTime()->format('YmdHis') . "_" . str_replace(" ", "_", $metier->getLibelle()) .**/ '.pdf';
        $filemane = "PrEECoG_Emargement.pdf";
        try {
            $exporter->getMpdf()->SetTitle($filemane);
        } catch (MpdfException $e) {
            throw new RuntimeException("Un problème est surevenu lors du changement de titre par MPDF.", 0, $e);
        }
        $exporter->export($filemane);
        exit;
    }

    public function exportTousEmargementsAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $journees = $instance->getJournees();
        $journees = array_filter($journees, function (FormationInstanceJournee $a) { return $a->estNonHistorise();});

        $exporter = new EmargementPdfExporter($this->renderer, 'A4');
        $exporter->setVars([]);
        $filemane = "formation_".$instance->getFormation()->getId()."_du_".str_replace("/","-",$instance->getDebut())."_au_".str_replace("/","-",$instance->getFin())."_emargements.pdf";
        $exporter->exportAll($journees, $filemane);
        exit;
    }

    /** INSCRIT ********************************************************************************************/

    public function ajouterAgentAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $inscrit = new FormationInstanceInscrit();
        $inscrit->setInstance($instance);

        $form = $this->getSelectionAgentForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/ajouter-agent', ['formulaire-instance' => $instance->getId()], [], true));
        $form->bind($inscrit);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (! $instance->hasAgent($inscrit->getAgent())) {
                    $inscrit->setListe($instance->getListeDisponible());
                    $this->getFormationInstanceInscritService()->create($inscrit);

                    $texte = ($instance->getListeDisponible() === FormationInstanceInscrit::PRINCIPALE) ? "principale" : "complémentaire";
                    $this->flashMessenger()->addSuccessMessage("L'agent <strong>" . $inscrit->getAgent()->getDenomination() . "</strong> vient d'être ajouté&middot;e en <strong>liste " . $texte . "</strong>.");
                } else {
                    $this->flashMessenger()->addErrorMessage("L'agent <strong>" . $inscrit->getAgent()->getDenomination() . "</strong> est déjà inscrit&middot;e à l'instance de formation.");
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un agent pour l'instance de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAgentAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $inscrit->setListe(null);
        $this->getFormationInstanceInscritService()->historise($inscrit);

        $this->flashMessenger()->addSuccessMessage("L'agent <strong>". $inscrit->getAgent()->getDenomination() ."</strong> vient d'être retiré&middot;e des listes.");

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    public function restaurerAgentAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        $liste = $inscrit->getInstance()->getListeDisponible();

        if ($liste !== null) {
            $inscrit->setListe($liste);
            $this->getFormationInstanceInscritService()->restore($inscrit);
        }
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    public function supprimerAgentAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationInstanceInscritService()->delete($inscrit);
            exit();
        }

        $vm = new ViewModel();
        if ($inscrit !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'inscription de [" . $inscrit->getAgent()->getDenomination() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer-agent', ["inscrit" => $inscrit->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function envoyerListePrincipaleAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        $inscrit->setListe(FormationInstanceInscrit::PRINCIPALE);
        $this->getFormationInstanceInscritService()->update($inscrit);

        $this->flashMessenger()->addSuccessMessage("L'agent <strong>". $inscrit->getAgent()->getDenomination() ."</strong> vient d'être ajouté&middot;e en liste principale.");

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    public function envoyerListeComplementaireAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        $inscrit->setListe(FormationInstanceInscrit::COMPLEMENTAIRE);
        $this->getFormationInstanceInscritService()->update($inscrit);

        $this->flashMessenger()->addSuccessMessage("L'agent <strong>". $inscrit->getAgent()->getDenomination() ."</strong> vient d'être ajouté&middot;e en liste complémentaire.");

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getInstance()->getId()], [], true);
    }

    /**
     * @throws MpdfException
     */
    public function genererConvocationAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        $this->getExporterService()->setVars([
            'type' => 'FORMATION_CONVOCATION',
            'agent' => $inscrit->getAgent(),
            'formation' => $inscrit->getInstance()->getFormation(),
            'instance' => $inscrit->getInstance(),
        ]);
        $this->getExporterService()->export('export.pdf');
        exit;
    }

    /**
     * @throws MpdfException
     */
    public function genererAttestationAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        $this->getExporterService()->setVars([
            'type' => 'FORMATION_ATTESTATION',
            'agent' => $inscrit->getAgent(),
            'formation' => $inscrit->getInstance()->getFormation(),
            'instance' => $inscrit->getInstance(),
        ]);
        $this->getExporterService()->export('export.pdf');
        exit;
    }

    /** FRIAS DE FORMATION ********************************************************************************************/

    public function renseignerFraisAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        if ($inscrit->getFrais() === null) {
            $frais = new FormationInstanceFrais();
            $frais->setInscrit($inscrit);
            $this->getFormationInstanceFraisService()->create($frais);
        }
        $frais = $inscrit->getFrais();

        $form = $this->getFormationInstanceFraisForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/renseigner-frais', ['inscrit' => $inscrit->getId()], [], true));
        $form->bind($frais);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceFraisService()->update($frais);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Saisie des frais de ".$inscrit->getAgent()->getDenomination(),
            'form' => $form,
        ]);
        return $vm;
    }

    /** PRESENCE AU FORMATION *****************************************************************************************/

    public function renseignerPresencesAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $presences = $this->getFormationInstancePresenceService()->getFormationInstancePresenceByInstance($instance);

        $dictionnaire = [];
        foreach ($presences as $presence) {
            $dictionnaire[$presence->getJournee()->getId()][$presence->getInscrit()->getId()] = $presence;
        }

        return new ViewModel([
            'instance' => $instance,
            'presences' => $dictionnaire,
        ]);
    }

    public function togglePresenceAction()
    {
        $journeeId = $this->params()->fromRoute('journee');
        $journee = $this->getFormationInstanceJourneeService()->getFormationInstanceJournee($journeeId);
        $inscritId = $this->params()->fromRoute('inscrit');
        $inscrit = $this->getFormationInstanceInscritService()->getFormationInstanceInscrit($inscritId);

        /** @var  FormationInstancePresence $presence */
        $presence = $this->getFormationInstancePresenceService()->getFormationInstancePresenceByJourneeAndInscrit($journee, $inscrit);
        if ($presence === null) {
            $presence = new FormationInstancePresence();
            $presence->setJournee($journee);
            $presence->setInscrit($inscrit);
            $presence->setPresent(true);
            $presence->setPresenceType("???");
            $this->getFormationInstancePresenceService()->create($presence);
        } else {
            $presence->setPresent(! $presence->isPresent());
            $this->getFormationInstancePresenceService()->update($presence);
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/reponse');
        $vm->setVariables([
            'reponse' => $presence->isPresent(),
        ]);
        return $vm;
    }

    public function togglePresencesAction()
    {
        $mode = $this->params()->fromRoute('mode');
        $inscritId = $this->params()->fromRoute('inscrit');
        $inscrit = $this->getFormationInstanceInscritService()->getFormationInstanceInscrit($inscritId);

        $instance = $inscrit->getInstance();
        $journees = $instance->getJournees();

        /** @var  FormationInstancePresence $presence */
        foreach ($journees as $journee) {
            $presence = $this->getFormationInstancePresenceService()->getFormationInstancePresenceByJourneeAndInscrit($journee, $inscrit);
            if ($presence === null) {
                $presence = new FormationInstancePresence();
                $presence->setJournee($journee);
                $presence->setInscrit($inscrit);
                $presence->setPresent($mode === 'on');
                $presence->setPresenceType("???");
                $this->getFormationInstancePresenceService()->create($presence);
            } else {
                $presence->setPresent($mode === 'on');
                $this->getFormationInstancePresenceService()->update($presence);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/reponse');
        $vm->setVariables([
            'reponse' => ($mode === 'on'),
        ]);
        return $vm;
    }

}