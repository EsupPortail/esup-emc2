<?php

namespace Application\Controller;

use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Form\RessourceRh\MissionSpecifiqueForm;
use Application\Service\Agent\AgentService;
use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueControllerFactory {

    public function __invoke(ContainerInterface $container) {

        /**
         * @var AgentService $agentService
         * @var RessourceRhService $ressourceService
         * @var StructureService $structureServuce
         * @var MissionSpecifiqueService $missionService
         */
        $missionService = $container->get(MissionSpecifiqueService::class);
        $agentService = $container->get(AgentService::class);
        $ressourceService = $container->get(RessourceRhService::class);
        $structureServuce = $container->get(StructureService::class);

        /**
         * @var MissionSpecifiqueForm $missionSpecifiqueForm
         * @var ModifierLibelleForm $modifierLibelleForm
         * @var AgentMissionSpecifiqueForm $agentMissionSpecifiqueForm
         */
        $missionSpecifiqueForm = $container->get('FormElementManager')->get(MissionSpecifiqueForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);

//        $agentMissionSpecifiqueForm = $container->get('FormElementManager')->get(AgentMissionSpecifiqueForm::class);

        /** @var MissionSpecifiqueController $controller */
        $controller = new MissionSpecifiqueController();
        $controller->setAgentService($agentService);
        $controller->setRessourceRhService($ressourceService);
        $controller->setStructureService($structureServuce);
        $controller->setMissionSpecifiqueService($missionService);

        $controller->setMissionSpecifiqueForm($missionSpecifiqueForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
//        $controller->setAgentMissionSpecifiqueForm($agentMissionSpecifiqueForm);
        return $controller;
    }
}