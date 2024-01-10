<?php

namespace Application\Controller;

use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use MissionSpecifique\Service\MissionSpecifique\MissionSpecifiqueService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenRenderer\Service\Rendu\RenduService;

class MissionSpecifiqueAffectationControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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