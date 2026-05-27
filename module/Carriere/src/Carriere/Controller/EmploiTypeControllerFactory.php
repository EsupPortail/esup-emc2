<?php

namespace Carriere\Controller;

use Agent\Service\AgentEmploiType\AgentEmploiTypeService;
use Carriere\Service\EmploiType\EmploiTypeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class EmploiTypeControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return EmploiTypeController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EmploiTypeController
    {
        /**
         * @var AgentEmploiTypeService $agentEmploiTypeService
         * @var EmploiTypeService $emploiTypeService
         * @var ParametreService $parametreService
         */
        $emploiTypeService = $container->get(EmploiTypeService::class);
        $agentEmploiTypeService = $container->get(AgentEmploiTypeService::class);
        $parametreService = $container->get(ParametreService::class);

        $controller = new EmploiTypeController();
        $controller->setAgentEmploiTypeService($agentEmploiTypeService);
        $controller->setEmploiTypeService($emploiTypeService);
        $controller->setParametreService($parametreService);
        return $controller;
    }
}