<?php

namespace Application\Controller\MissionSpecifique;

use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Service\Agent\AgentService;
use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\Structure\StructureService;
use Zend\Mvc\Controller\ControllerManager;

class MissionSpecifiqueControllerFactory {

    public function __invoke(ControllerManager $manager) {

        /**
         * @var AgentService $agentService
         * @var RessourceRhService $ressourceService
         * @var StructureService $structureServuce
         * @var MissionSpecifiqueService $missionService
         */
        $missionService = $manager->getServiceLocator()->get(MissionSpecifiqueService::class);
        $agentService = $manager->getServiceLocator()->get(AgentService::class);
        $ressourceService = $manager->getServiceLocator()->get(RessourceRhService::class);
        $structureServuce = $manager->getServiceLocator()->get(StructureService::class);

        /**
         * @var AgentMissionSpecifiqueForm $agentMissionSpecifiqueForm
         */
        $agentMissionSpecifiqueForm = $manager->getServiceLocator()->get('FormElementManager')->get(AgentMissionSpecifiqueForm::class);

        /** @var MissionSpecifiqueController $controller */
        $controller = new MissionSpecifiqueController();
        $controller->setAgentService($agentService);
        $controller->setRessourceRhService($ressourceService);
        $controller->setStructureService($structureServuce);
        $controller->setMissionSpecifiqueService($missionService);
        $controller->setAgentMissionSpecifiqueForm($agentMissionSpecifiqueForm);
        return $controller;
    }
}