<?php

namespace Application\Controller;

use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\MissionSpecifique;
use Application\Entity\Db\MissionSpecifiqueTheme;
use Application\Entity\Db\MissionSpecifiqueType;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormAwareTrait;
use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Application\Form\RessourceRh\MissionSpecifiqueFormAwareTrait;
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
    use MissionSpecifiqueFormAwareTrait;

    use AgentMissionSpecifiqueFormAwareTrait;

    use ModifierLibelleFormAwareTrait;

    /** Partie gestion des missions spécifiques  **********************************************************************/

    public function indexAction()
    {
        $missions = $this->getMissionSpecifiqueService()->getMissionsSpecifiques();
        $types = $this->getMissionSpecifiqueService()->getMissionsSpecifiquesTypes();
        $themes = $this->getMissionSpecifiqueService()->getMissionsSpecifiquesThemes();

        return new ViewModel([
            'missions' => $missions,
            'types' => $types,
            'themes' => $themes,
        ]);
    }

    /** Missions */

    public function afficherMissionAction() {
        $mission = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifique($this);

        return new ViewModel([
            'title' => "Affichage d'une mission spécifique",
            'mission' => $mission,
        ]);
    }

    public function ajouterMissionAction()
    {
        $mission = new MissionSpecifique();
        $form = $this->getMissionSpecifiqueForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-specifique/mission/ajouter'));
        $form->bind($mission);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueService()->create($mission);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une mission spécifique",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierMissionAction()
    {
        $mission = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifique($this);
        $form = $this->getMissionSpecifiqueForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-specifique/mission/modifier', ['mission' => $mission->getId()], [], true));
        $form->bind($mission);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueService()->update($mission);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification de la mission spécifique",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserMissionAction()
    {
        $mission = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifique($this);
        $this->getMissionSpecifiqueService()->historise($mission);
        return $this->redirect()->toRoute('mission-specifique', [], ["fragment" => "mission"], true);
    }

    public function restaurerMissionAction()
    {
        $mission = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifique($this);
        $this->getMissionSpecifiqueService()->restore($mission);
        return $this->redirect()->toRoute('mission-specifique', [], ["fragment" => "mission"], true);
    }

    public function detruireMissionAction()
    {
        $mission = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifique($this);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMissionSpecifiqueService()->delete($mission);
            exit();
        }

        $vm = new ViewModel();
        if ($mission !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la mission spécifique  " . $mission->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('mission-specifique/mission/detruire', ["mission" => $mission->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Types */

    public function afficherTypeAction() {
        $type = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifiqueType($this);

        return new ViewModel([
            'title' => "Affichage d'un type de mission spécifique",
            'type' => $type,
        ]);
    }

    public function ajouterTypeAction()
    {
        $type = new MissionSpecifiqueType();
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-specifique/type/ajouter'));
        $form->bind($type);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueService()->createType($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un type de mission spécifique",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierTypeAction()
    {
        $type = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifiqueType($this);
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-specifique/type/modifier', ['type' => $type->getId()], [], true));
        $form->bind($type);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueService()->updateType($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un type de mission spécifique",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserTypeAction()
    {
        $type = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifiqueType($this);
        $this->getMissionSpecifiqueService()->historiseType($type);
        return $this->redirect()->toRoute('mission-specifique', [], ["fragment" => "type"], true);
    }

    public function restaurerTypeAction()
    {
        $type = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifiqueType($this);
        $this->getMissionSpecifiqueService()->restoreType($type);
        return $this->redirect()->toRoute('mission-specifique', [], ["fragment" => "type"], true);
    }

    public function detruireTypeAction()
    {
        $type = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifiqueType($this);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMissionSpecifiqueService()->deleteType($type);
            exit();
        }

        $vm = new ViewModel();
        if ($type !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du type de mission spécifique  " . $type->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('mission-specifique/type/detruire', ["type" => $type->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Thèmes */

    public function afficherThemeAction() {
        $theme = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifiqueTheme($this);

        return new ViewModel([
            'title' => "Affichage d'un theme de mission spécifique",
            'theme' => $theme,
        ]);
    }

    public function ajouterThemeAction()
    {
        $theme = new MissionSpecifiqueTheme();
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-specifique/theme/ajouter'));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueService()->createTheme($theme);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un thème de mission spécifique",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierThemeAction()
    {
        $theme = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifiqueTheme($this);
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-specifique/theme/modifier', ['theme' => $theme->getId()], [], true));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueService()->updateTheme($theme);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un thème de mission spécifique",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserThemeAction()
    {
        $theme = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifiqueTheme($this);
        $this->getMissionSpecifiqueService()->historiseTheme($theme);
        return $this->redirect()->toRoute('mission-specifique', [], ["fragment" => "theme"], true);
    }

    public function restaurerThemeAction()
    {
        $theme = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifiqueTheme($this);
        $this->getMissionSpecifiqueService()->restoreTheme($theme);
        return $this->redirect()->toRoute('mission-specifique', [], ["fragment" => "theme"], true);
    }

    public function detruireThemeAction()
    {
        $theme = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifiqueTheme($this);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMissionSpecifiqueService()->deleteTheme($theme);
            exit();
        }

        $vm = new ViewModel();
        if ($theme !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du thème de mission spécifique  " . $theme->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('mission-specifique/theme/detruire', ["theme" => $theme->getId()], [], true),
            ]);
        }
        return $vm;
    }


    /** AFFECTATION ***************************************************************************************************/

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
            'title' => "Affichage de l'affectation",
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