<?php

namespace Application\Controller;

use Application\Entity\Db\FormationInstance;
use Application\Entity\Db\FormationInstanceInscrit;
use Formation\Entity\Db\FormationInstanceJournee;
use Application\Form\FormationInstance\FormationInstanceFormAwareTrait;
use Formation\Form\FormationJournee\FormationJourneeFormAwareTrait;
use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Service\Export\Formation\Emargement\EmargementPdfExporter;
use Application\Service\Formation\FormationServiceAwareTrait;
use Application\Service\FormationInstance\FormationInstanceInscritServiceAwareTrait;
use Application\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceFormateur\FormationInstanceFormateurServiceAwareTrait;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeServiceAwareTrait;
use Formation\Service\FormationInstancePresence\FormationInstancePresenceAwareTrait;
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
    use FormationInstanceFormateurServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use FormationInstanceJourneeServiceAwareTrait;
    use FormationInstancePresenceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;
    use FormationInstanceFormAwareTrait;
    use FormationJourneeFormAwareTrait;
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
            'inscrit' => $inscrit,
            'formation' => $inscrit->getInstance()->getFormation(),
            'instance' => $inscrit->getInstance(),
        ]);
        $this->getExporterService()->export('export.pdf');
        exit;
    }

    /** Question suite à la formation */

    public function renseignerQuestionnaireAction()
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
}