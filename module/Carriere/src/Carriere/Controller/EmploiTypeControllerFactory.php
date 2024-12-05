<?php

namespace Carriere\Controller;

use Agent\Service\AgentGrade\AgentGradeService;
use Carriere\Service\EmploiType\EmploiTypeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class EmploiTypeControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return EmploiTypeController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : EmploiTypeController
    {
        /**
         * @var AgentGradeService $agentGradeService
         * @var EmploiTypeService $emploiTypeService
         * @var ParametreService $parametreService
         */
        $agentGradeService = $container->get(AgentGradeService::class);
        $emploiTypeService = $container->get(EmploiTypeService::class);
        $parametreService = $container->get(ParametreService::class);

        $controller = new EmploiTypeController();
        $controller->setAgentGradeService($agentGradeService);
        $controller->setEmploiTypeService($emploiTypeService);
        $controller->setParametreService($parametreService);
        return $controller;
    }
}