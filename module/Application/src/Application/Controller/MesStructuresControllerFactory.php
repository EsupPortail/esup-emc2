<?php

namespace Application\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Application\Service\Poste\PosteService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class MesStructuresControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var FichePosteService $fichePosteService
         * @var MissionSpecifiqueService $missionService
         * @var PosteService $posteService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $missionService = $container->get(MissionSpecifiqueService::class);
        $posteService = $container->get(PosteService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var MesStructuresController $controller */
        $controller = new MesStructuresController();
        $controller->setAgentService($agentService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setMissionSpecifiqueService($missionService);
        $controller->setPosteService($posteService);
        $controller->setStructureService($structureService);
        $controller->setUserService($userService);
        return $controller;
    }
}