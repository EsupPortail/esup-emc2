<?php

namespace Application\Controller;

use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Service\Agent\AgentService;
use Application\Service\MissionSpecifique\MissionSpecifiqueAffectationService;
use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use UnicaenDocument\Service\Exporter\ExporterService;

class MissionSpecifiqueAffectationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return MissionSpecifiqueAffectationController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var ExporterService $exporterService
         * @var MissionSpecifiqueService $missionSpecifiqueService
         * @var MissionSpecifiqueAffectationService $missionSpecifiqueAffectationService
         * @var StructureService $structureService
         */
        $agentService = $container->get(AgentService::class);
        $exporterService = $container->get(ExporterService::class);
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
        $controller->setExporterService($exporterService);
        $controller->setMissionSpecifiqueService($missionSpecifiqueService);
        $controller->setMissionSpecifiqueAffectationService($missionSpecifiqueAffectationService);
        $controller->setStructureService($structureService);

        $controller->setAgentMissionSpecifiqueForm($agentMissionSpecifiqueForm);

        return $controller;
    }
}