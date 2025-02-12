<?php

namespace Agent\Controller;

use Agent\Service\AgentAffectation\AgentAffectationService;
use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Laminas\Mvc\Controller\AbstractActionController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\User\UserService;


class AutoriteControllerFactory extends AbstractActionController
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AutoriteController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAffectationService $agentAffectationService
         * @var AgentAutoriteService $agentAutoriteService
         * @var CampagneService $campagneService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $campagneService = $container->get(CampagneService::class);
        $userService = $container->get(UserService::class);

        $controller = new AutoriteController();
        $controller->setAgentService($agentService);
        $controller->setAgentAffectationService($agentAffectationService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setCampagneService($campagneService);
        $controller->setUserService($userService);
        return $controller;
    }
}