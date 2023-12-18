<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Formation\Service\StagiaireExterne\StagiaireExterneService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenUtilisateur\Service\User\UserService;

class IndexControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return IndexController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): IndexController
    {
        /**
         * @var AgentService $agentService
         * @var RenduService $renduService
         * @var StagiaireExterneService $stagiaireExterneService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $renduService = $container->get(RenduService::class);
        $stagiaireExterneService = $container->get(StagiaireExterneService::class);
        $userService = $container->get(UserService::class);

        $controller = new IndexController();
        $controller->setAgentService($agentService);
        $controller->setRenduService($renduService);
        $controller->setStagiaireExterneService($stagiaireExterneService);
        $controller->setUserService($userService);
        return $controller;
    }
}