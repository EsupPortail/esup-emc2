<?php

namespace Application\Controller;

use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use MissionSpecifique\Service\MissionSpecifique\MissionSpecifiqueService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenUtilisateur\Service\User\UserService;

class MissionSpecifiqueAffectationControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MissionSpecifiqueAffectationController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentMissionSpecifiqueService $agentMissionSpecifiqueService
         * @var AgentSuperieurService $agentSuperieurService
         * @var RenduService $renduService
         * @var MissionSpecifiqueService $missionSpecifiqueService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentMissionSpecifiqueService = $container->get(AgentMissionSpecifiqueService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $renduService = $container->get(RenduService::class);
        $missionSpecifiqueService = $container->get(MissionSpecifiqueService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var AgentMissionSpecifiqueForm $agentMissionSpecifiqueForm
         */
        $agentMissionSpecifiqueForm = $container->get('FormElementManager')->get(AgentMissionSpecifiqueForm::class);

        $controller = new MissionSpecifiqueAffectationController();

        $controller->setAgentService($agentService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentMissionSpecifiqueService($agentMissionSpecifiqueService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setRenduService($renduService);
        $controller->setMissionSpecifiqueService($missionSpecifiqueService);
        $controller->setStructureService($structureService);
        $controller->setUserService($userService);

        $controller->setAgentMissionSpecifiqueForm($agentMissionSpecifiqueForm);

        return $controller;
    }
}