<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\User\UserService;

class AgentControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentController
    {
        /**
         * @var AgentService $agentService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $userService = $container->get(UserService::class);

        $controller = new AgentController();
        $controller->setAgentService($agentService);
        $controller->setUserService($userService);
        return $controller;
    }
}