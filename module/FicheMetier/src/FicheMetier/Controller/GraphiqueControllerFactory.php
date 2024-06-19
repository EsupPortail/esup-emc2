<?php

namespace FicheMetier\Controller;

use Application\Service\Agent\AgentService;
use Element\Service\Niveau\NiveauService;
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
         * @var NiveauService $niveauService
         */
        $agentService = $container->get(AgentService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $niveauService = $container->get(NiveauService::class);

        $controller = new GraphiqueController();
        $controller->setAgentService($agentService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setNiveauService($niveauService);
        return $controller;
    }
}