<?php

namespace Application\Controller;

use Application\Entity\Db\AgentMissionSpecifique;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MissionSpecifiqueController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use RessourceRhServiceAwareTrait;
    use StructureServiceAwareTrait;

    use MissionSpecifiqueServiceAwareTrait;
    use AgentMissionSpecifiqueFormAwareTrait;

    public function affectationAction()
    {
        $agentId      = $this->params()->fromQuery('agent');
        $agent        = $this->getAgentService()->getAgent($agentId);
        $structureId  = $this->params()->fromQuery('structure');
        $structure    = $this->getStructureService()->getStructure($structureId);
        $missionId    = $this->params()->fromQuery('mission');
        $mission      = $this->getRessourceRhService()->getMissionSpecifique($missionId);

        $affectations = $this->getMissionSpecifiqueService()->getAffectations($agent, $mission, $structure);

        $structures  = $this->getStructureService()->getStructuresAsOptions();
        $agents      = $this->getAgentService()->getAgentsAsOption();
        $missions    = $this->getRessourceRhService()->getMisssionsSpecifiquesAsOption();

        return new ViewModel([
            'agent' => $agent,
            'structure' => $structure,
            'mission' => $mission,
            'affectations' => $affectations,

            'structures' => $structures,
            'missions' => $missions,
            'agents' => $agents,
        ]);
    }

    public function ajouterAction() {

        $structureId = $this->params()->fromQuery('structure');
        $structure = $this->getStructureService()->getStructure($structureId);

        $affectation = new AgentMissionSpecifique();
        $form = $this->getAgentMissionSpecifiqueForm();
        if ($structure === null) {
            $form->setAttribute('action', $this->url()->fromRoute('agent-mission-specifique/ajouter', [], [], true));
        } else {
            $form = $form->reinitWithStructure($structure, true);
            $form->setAttribute('action', $this->url()->fromRoute('agent-mission-specifique/ajouter', [], ["query" =>["structure" => $structure->getId()]], true));
        }
        $form->bind($affectation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueService()->create($affectation);
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

    public function afficherAction() {
        $affectation = $this->getMissionSpecifiqueService()->getRequestedAffectation($this);

        $vm = new ViewModel();
        $vm->setVariables([
            'title' => 'Affichage de l\'affection',
            'affectation' => $affectation,
        ]);
        return $vm;
    }

    public function editerAction() {

        $structureId = $this->params()->fromQuery('structure');
        $structure = $this->getStructureService()->getStructure($structureId);

        $affectation = $this->getMissionSpecifiqueService()->getRequestedAffectation($this);
        $form = $this->getAgentMissionSpecifiqueForm();
        if ($structure === null) {
            $form->setAttribute('action', $this->url()->fromRoute('agent-mission-specifique/editer', [], [], true));
        } else {
            $form = $form->reinitWithStructure($structure, true);
            $form->setAttribute('action', $this->url()->fromRoute('agent-mission-specifique/editer', [], ["query" =>["structure" => $structure->getId()]], true));
        }
        $form->bind($affectation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueService()->update($affectation);
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
        $affectation = $this->getMissionSpecifiqueService()->getRequestedAffectation($this);
        $this->getMissionSpecifiqueService()->historise($affectation);
        return $this->redirect()->toRoute('agent-mission-specifique', [], [], true);
    }

    public function restaurerAction() {
        $affectation = $this->getMissionSpecifiqueService()->getRequestedAffectation($this);
        $this->getMissionSpecifiqueService()->restore($affectation);
        return $this->redirect()->toRoute('agent-mission-specifique', [], [], true);
    }

    public function detruireAction()
    {
        $affectation = $this->getMissionSpecifiqueService()->getRequestedAffectation($this);
        $structureId = $this->params()->fromQuery('structure');
        $structure = $this->getStructureService()->getStructure($structureId);
        $params = [];
        if ($structure !== null) $params["structure"] = $structure->getId();

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMissionSpecifiqueService()->delete($affectation);
//            return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($affectation != null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'affectation de " . $affectation->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent-mission-specifique/detruire', ["affectation" => $affectation->getId()], ["query" => $params], true),
            ]);
        }
        return $vm;
    }
}