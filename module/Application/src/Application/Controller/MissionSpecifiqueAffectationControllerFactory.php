<?php

namespace Application\Controller;

use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Service\Agent\AgentService;
use Application\Service\MissionSpecifique\MissionSpecifiqueAffectationService;
use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use UnicaenRenderer\Service\Rendu\RenduService;

class MissionSpecifiqueAffectationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return MissionSpecifiqueAffectationController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var RenduService $renduService
         * @var MissionSpecifiqueService $missionSpecifiqueService
         * @var MissionSpecifiqueAffectationService $missionSpecifiqueAffectationService
         * @var StructureService $structureService
         */
        $agentService = $container->get(AgentService::class);
        $renduService = $container->get(RenduService::class);
        $missionSpecifiqueService = $container->get(MissionSpecifiqueService::class);
        $missionSpecifiqueAffectationService = $container->get(MissionSpecifiqueAffectationService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var AgentMissionSpecifiqueForm $agentMissionSpecifiqueForm
         */
        $agentMissionSpecifiqueForm = $container->get('FormElementManager')->get(AgentMissionSpecifiqueForm::class);

        /** @var MissionSpecifiqueAffectationController $controller */
        $controller = new MissionSpecifiqueAffectationController();

        $controller->setAgentService($agentService);
        $controller->setRenduService($renduService);
        $controller->setMissionSpecifiqueService($missionSpecifiqueService);
        $controller->setMissionSpecifiqueAffectationService($missionSpecifiqueAffectationService);
        $controller->setStructureService($structureService);

        $controller->setAgentMissionSpecifiqueForm($agentMissionSpecifiqueForm);

        return $controller;
    }
}