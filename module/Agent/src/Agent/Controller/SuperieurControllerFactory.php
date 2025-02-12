<?php

namespace Agent\Controller;

use Agent\Service\AgentAffectation\AgentAffectationService;
use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Laminas\Mvc\Controller\AbstractActionController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\User\UserService;


class SuperieurControllerFactory extends AbstractActionController
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SuperieurController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAffectationService $agentAffectationService
         * @var AgentSuperieurService $agentSuperieurService
         * @var CampagneService $campagneService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $campagneService = $container->get(CampagneService::class);
        $userService = $container->get(UserService::class);

        $controller = new SuperieurController();
        $controller->setAgentService($agentService);
        $controller->setAgentAffectationService($agentAffectationService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setCampagneService($campagneService);
        $controller->setUserService($userService);
        return $controller;
    }
}