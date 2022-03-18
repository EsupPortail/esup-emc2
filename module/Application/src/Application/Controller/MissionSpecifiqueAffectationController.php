<?php

namespace Application\Controller;

use Application\Entity\Db\AgentMissionSpecifique;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueServiceAwareTrait;
use Application\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MissionSpecifiqueAffectationController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use AgentMissionSpecifiqueServiceAwareTrait;
    use RenduServiceAwareTrait;
    use MissionSpecifiqueServiceAwareTrait;
    use StructureServiceAwareTrait;

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
        $affectations = $this->getAgentMissionSpecifiqueService()->getAgentMissionsSpecifiquesByAgentAndMissionAndStructure($agent, $mission, $structure, false);
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
        $affectation = $this->getAgentMissionSpecifiqueService()->getRequestedAgentMissionSpecifique($this);

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
            $form->setAttribute('action', $this->url()->fromRoute('mission-specifique-affectation/ajouter', [], [], true));
        } else {
            $form->setAttribute('action', $this->url()->fromRoute('mission-specifique-affectation/ajouter', [], ["query" =>["structure" => $structure->getId()]], true));
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
                $this->getAgentMissionSpecifiqueService()->create($affectation);
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

        $affectation = $this->getAgentMissionSpecifiqueService()->getRequestedAgentMissionSpecifique($this);
        $form = $this->getAgentMissionSpecifiqueForm();
        /** @var SearchAndSelect $agentSS */
        $agentSS = $form->get('agent');
        if ($structure === null) {
            $form->setAttribute('action', $this->url()->fromRoute('mission-specifique-affectation/modifier', [], [], true));
        } else {
            $form->setAttribute('action', $this->url()->fromRoute('mission-specifique-affectation/modifier', [], ["query" =>["structure" => $structure->getId()]], true));
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
                $this->getAgentMissionSpecifiqueService()->update($affectation);
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
        $affectation = $this->getAgentMissionSpecifiqueService()->getRequestedAgentMissionSpecifique($this);
        $this->getAgentMissionSpecifiqueService()->historise($affectation);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('mission-specifique-affectation', [], [], true);
    }

    public function restaurerAction() {
        $affectation = $this->getAgentMissionSpecifiqueService()->getRequestedAgentMissionSpecifique($this);
        $this->getAgentMissionSpecifiqueService()->restore($affectation);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('mission-specifique-affectation', [], [], true);
    }

    public function detruireAction()
    {
        $affectation = $this->getAgentMissionSpecifiqueService()->getRequestedAgentMissionSpecifique($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getAgentMissionSpecifiqueService()->delete($affectation);
            exit();
        }

        $vm = new ViewModel();
        if ($affectation != null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'affectation de " . $affectation->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('mission-specifique-affectation/detruire', ["affectation" => $affectation->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function genererLettreTypeAction()
    {
        $affectation = $this->getAgentMissionSpecifiqueService()->getRequestedAgentMissionSpecifique($this);

        $vars = [
            'agent' => $affectation->getAgent(),
            'mission' => $affectation->getMission(),
            'structure' => $affectation->getStructure(),
            'affectation' => $affectation,
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode('MISSION_SPECIFIQUE_LETTRE', $vars);

        $exporter = new PdfExporter();
        $exporter->getMpdf()->SetTitle($rendu->getSujet());
        $exporter->setHeaderScript('');
        $exporter->setFooterScript('');
        $exporter->addBodyHtml($rendu->getCorps());
        return $exporter->export($rendu->getSujet(), PdfExporter::DESTINATION_BROWSER, null);
    }
}