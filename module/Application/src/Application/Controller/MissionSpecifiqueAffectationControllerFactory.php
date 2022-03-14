<?php

namespace Application\Controller;

use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Interop\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;
use UnicaenRenderer\Service\Rendu\RenduService;

class MissionSpecifiqueAffectationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return MissionSpecifiqueAffectationController
     */
    public function __invoke(ContainerInterface $container) : MissionSpecifiqueAffectationController
    {
        /**
         * @var AgentService $agentService
         * @var AgentMissionSpecifiqueService $agentMissionSpecifiqueService
         * @var RenduService $renduService
         * @var MissionSpecifiqueService $missionSpecifiqueService
         * @var StructureService $structureService
         */
        $agentService = $container->get(AgentService::class);
        $agentMissionSpecifiqueService = $container->get(AgentMissionSpecifiqueService::class);
        $renduService = $container->get(RenduService::class);
        $missionSpecifiqueService = $container->get(MissionSpecifiqueService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var AgentMissionSpecifiqueForm $agentMissionSpecifiqueForm
         */
        $agentMissionSpecifiqueForm = $container->get('FormElementManager')->get(AgentMissionSpecifiqueForm::class);

        /** @var MissionSpecifiqueAffectationController $controller */
        $controller = new MissionSpecifiqueAffectationController();

        $controller->setAgentService($agentService);
        $controller->setAgentMissionSpecifiqueService($agentMissionSpecifiqueService);
        $controller->setRenduService($renduService);
        $controller->setMissionSpecifiqueService($missionSpecifiqueService);
        $controller->setStructureService($structureService);

        $controller->setAgentMissionSpecifiqueForm($agentMissionSpecifiqueForm);

        return $controller;
    }
}