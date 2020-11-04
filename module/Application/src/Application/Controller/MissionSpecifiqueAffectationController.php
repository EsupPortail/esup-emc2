<?php

namespace Application\Controller;

use Application\Entity\Db\AgentMissionSpecifique;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\MissionSpecifique\MissionSpecifiqueAffectationServiceAwareTrait;
use Application\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Mpdf\MpdfException;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenDocument\Service\Exporter\ExporterServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MissionSpecifiqueAffectationController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use ExporterServiceAwareTrait;
    use MissionSpecifiqueServiceAwareTrait;
    use StructureServiceAwareTrait;
    use MissionSpecifiqueAffectationServiceAwareTrait;

    use AgentMissionSpecifiqueFormAwareTrait;

    public function indexAction()
    {
        $fromQueries  = $this->params()->fromQuery();
        $agentId      = $fromQueries['agent'];
        $structureId  = $fromQueries['structure'];
        $missionId    = $fromQueries['mission'];
        $agent        = $this->getAgentService()->getAgent($agentId);
        $structure    = $this->getStructureService()->getStructure($structureId);
        $mission      = $this->getMissionSpecifiqueService()->getMissionSpecifique($missionId);
        $affectations = $this->getMissionSpecifiqueAffectationService()->getAffectations($agent, $mission, $structure);
        $missions    = $this->getMissionSpecifiqueService()->getMisssionsSpecifiquesAsOptions();

        return new ViewModel([
            'agent' => $agent,
            'structure' => $structure,
            'mission' => $mission,
            'affectations' => $affectations,

            'missions' => $missions,
        ]);
    }

    public function afficherAction() {
        $affectation = $this->getMissionSpecifiqueAffectationService()->getRequestedAffectation($this);

        $vm = new ViewModel();
        $vm->setVariables([
            'title' => "Affichage de l'affectation",
            'affectation' => $affectation,
        ]);
        return $vm;
    }

    public function ajouterAction()
    {
        $structureId = $this->params()->fromQuery('structure');
        $structure = $this->getStructureService()->getStructure($structureId);
        $agentId = $this->params()->fromQuery('agent');
        $agent = $this->getAgentService()->getAgent($agentId);

        $affectation = new AgentMissionSpecifique();
        /** @var AgentMissionSpecifiqueForm $form */
        $form = $this->getAgentMissionSpecifiqueForm();
        /** @var SearchAndSelect $agentSS */
        $agentSS = $form->get('agent');

        if ($structure === null) {
            $form->setAttribute('action', $this->url()->fromRoute('mission-specifique/affectation/ajouter', [], [], true));
        } else {
            $form->setAttribute('action', $this->url()->fromRoute('mission-specifique/affectation/ajouter', [], ["query" =>["structure" => $structure->getId()]], true));
            /** @var SearchAndSelect $structureSS */
            $structureSS = $form->get('structure');
            /** @see StructureController::rechercherWithStructureMereAction() */
            $structureSS->setAutocompleteSource($this->url()->fromRoute('structure/rechercher-with-structure-mere', ['structure' => $structure->getId()], [], true));
            /** @see AgentController::rechercherWithStructureMereAction() */
            $agentSS->setAutocompleteSource($this->url()->fromRoute('agent/rechercher-with-structure-mere', ['structure' => $structure->getId()], [], true));
        }
        if ($agent !== null) {
            $affectation->setAgent($agent);
            $agentSS->setAttribute('readonly', true);
        }
        $form->bind($affectation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueAffectationService()->create($affectation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Affectation d\'une nouvelle mission spécifique à un agent',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $structureId = $this->params()->fromQuery('structure');
        $structure = $this->getStructureService()->getStructure($structureId);
        $agentId = $this->params()->fromQuery('agent');
        $agent = $this->getAgentService()->getAgent($agentId);

        $affectation = $this->getMissionSpecifiqueAffectationService()->getRequestedAffectation($this);
        $form = $this->getAgentMissionSpecifiqueForm();
        /** @var SearchAndSelect $agentSS */
        $agentSS = $form->get('agent');
        if ($structure === null) {
            $form->setAttribute('action', $this->url()->fromRoute('mission-specifique/affectation/modifier', [], [], true));
        } else {
            $form->setAttribute('action', $this->url()->fromRoute('mission-specifique/affectation/modifier', [], ["query" =>["structure" => $structure->getId()]], true));
            /** @see AgentController::rechercherWithStructureMereAction() */
            $agentSS->setAutocompleteSource($this->url()->fromRoute('agent/rechercher-with-structure-mere', ['structure' => $structure->getId()], [], true));
            /** @var SearchAndSelect $structureSS */
            $structureSS = $form->get('structure');
            /** @see StructureController::rechercherWithStructureMereAction() */
            $structureSS->setAutocompleteSource($this->url()->fromRoute('structure/rechercher-with-structure-mere', ['structure' => $structure->getId()], [], true));
        }
        if ($agent !== null) {
            $affectation->setAgent($agent);
            $agentSS->setAttribute('readonly', true);
        }
        $form->bind($affectation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueAffectationService()->update($affectation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modification d\'une mission spécifique d\'un agent',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() {
        $affectation = $this->getMissionSpecifiqueAffectationService()->getRequestedAffectation($this);
        $this->getMissionSpecifiqueAffectationService()->historise($affectation);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('mission-specifique/affectation', [], [], true);
    }

    public function restaurerAction() {
        $affectation = $this->getMissionSpecifiqueAffectationService()->getRequestedAffectation($this);
        $this->getMissionSpecifiqueAffectationService()->restore($affectation);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('mission-specifique/affectation', [], [], true);
    }

    public function detruireAction()
    {
        $affectation = $this->getMissionSpecifiqueAffectationService()->getRequestedAffectation($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMissionSpecifiqueAffectationService()->delete($affectation);
            exit();
        }

        $vm = new ViewModel();
        if ($affectation != null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'affectation de " . $affectation->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('mission-specifique/affectation/detruire', ["affectation" => $affectation->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /**
     * @throws MpdfException
     */
    public function genererLettreTypeAction()
    {
        $affectation = $this->getMissionSpecifiqueAffectationService()->getRequestedAffectation($this);

        $this->getExporterService()->setVars([
            'type' => 'MISSION_SPECIFIQUE_LETTRE',
            'agent' => $affectation->getAgent(),
            'mission' => $affectation->getMission(),
            'structure' => $affectation->getStructure(),
            'affectation' => $affectation,
        ]);
        $this->getExporterService()->export('export.pdf');
        exit;
    }
}