<?php

namespace FicheMetier\Controller;

use Application\Service\Agent\AgentService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class GraphiqueControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : GraphiqueController
    {
        /**
         * @var AgentService $agentService
         * @var FicheMetierService $ficheMetierService
         */
        $agentService = $container->get(AgentService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);

        $controller = new GraphiqueController();
        $controller->setAgentService($agentService);
        $controller->setFicheMetierService($ficheMetierService);
        return $controller;
    }
}