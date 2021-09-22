<?php

namespace Application\Controller;

use Application\Entity\Db\AgentMissionSpecifique;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\MissionSpecifique\MissionSpecifiqueAffectationServiceAwareTrait;
use Application\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Contenu\ContenuServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MissionSpecifiqueAffectationController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use ContenuServiceAwareTrait;
    use MissionSpecifiqueServiceAwareTrait;
    use StructureServiceAwareTrait;
    use MissionSpecifiqueAffectationServiceAwareTrait;

    use AgentMissionSpecifiqueFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $fromQueries  = $this->params()->fromQuery();
        $agentId      = $fromQueries['agent'];
        $structureId  = $fromQueries['structure'];
        $missionId    = $fromQueries['mission'];
        $agent        = ($agentId !== '')?$this->getAgentService()->getAgent($agentId):null;
        $structure    = ($structureId !== '')?$this->getStructureService()->getStructure($structureId):null;
        $mission      = ($missionId !== '')?$this->getMissionSpecifiqueService()->getMissionSpecifique($missionId):null;
        $affectations = $this->getMissionSpecifiqueAffectationService()->getAffectations($agent, $mission, $structure);
        $missions    = $this->getMissionSpecifiqueService()->getMissionsSpecifiques();

        return new ViewModel([
            'agent' => $agent,
            'structure' => $structure,
            'mission' => $mission,
            'affectations' => $affectations,

            'missions' => $missions,
        ]);
    }

    public function afficherAction() : ViewModel
    {
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

    public function genererLettreTypeAction()
    {
        $affectation = $this->getMissionSpecifiqueAffectationService()->getRequestedAffectation($this);

        $contenu = $this->getContenuService()->getContenuByCode("MISSION_SPECIFIQUE_LETTRE");
        $vars = [
            'agent' => $affectation->getAgent(),
            'mission' => $affectation->getMission(),
            'structure' => $affectation->getStructure(),
            'affectation' => $affectation,
        ];
        $titre = $this->getContenuService()->generateTitre($contenu, $vars);
        $texte = $this->getContenuService()->generateContenu($contenu, $vars);
        $complement = $this->getContenuService()->generateComplement($contenu, $vars);

        $exporter = new PdfExporter();
        $exporter->getMpdf()->SetTitle($titre);
        $exporter->setHeaderScript('');
        $exporter->setFooterScript('');
        $exporter->addBodyHtml($texte);
        return $exporter->export($complement, PdfExporter::DESTINATION_BROWSER, null);
    }
}