<?php

namespace Application\Controller;

use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Service\Agent\AgentService;
use Application\Service\MissionSpecifique\MissionSpecifiqueAffectationService;
use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueAffectationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return MissionSpecifiqueAffectationController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var MissionSpecifiqueService $missionSpecifiqueService
         * @var StructureService $structureService
         * @var MissionSpecifiqueAffectationService $missionSpecifiqueAffectationService
         */
        $agentService = $container->get(AgentService::class);
        $missionSpecifiqueService = $container->get(MissionSpecifiqueService::class);
        $structureService = $container->get(StructureService::class);
        $missionSpecifiqueAffectationService = $container->get(MissionSpecifiqueAffectationService::class);

        /**
         * @var AgentMissionSpecifiqueForm $agentMissionSpecifiqueForm
         */
        $agentMissionSpecifiqueForm = $container->get('FormElementManager')->get(AgentMissionSpecifiqueForm::class);

        /** @var MissionSpecifiqueAffectationController $controller */
        $controller = new MissionSpecifiqueAffectationController();

        $controller->setAgentService($agentService);
        $controller->setMissionSpecifiqueService($missionSpecifiqueService);
        $controller->setStructureService($structureService);
        $controller->setMissionSpecifiqueAffectationService($missionSpecifiqueAffectationService);

        $controller->setAgentMissionSpecifiqueForm($agentMissionSpecifiqueForm);

        return $controller;
    }
}