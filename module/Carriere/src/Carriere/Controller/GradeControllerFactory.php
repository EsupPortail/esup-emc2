<?php

namespace Carriere\Controller;

use Agent\Service\AgentGrade\AgentGradeService;
use Carriere\Service\Grade\GradeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class GradeControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return GradeController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : GradeController
    {
        /**
         * @var AgentGradeService $agentGradeService
         * @var GradeService $gradeService
         * @var ParametreService $parametreService
         */
        $agentGradeService = $container->get(AgentGradeService::class);
        $gradeService = $container->get(GradeService::class);
        $parametreService = $container->get(ParametreService::class);

        $controller = new GradeController();
        $controller->setAgentGradeService($agentGradeService);
        $controller->setGradeService($gradeService);
        $controller->setParametreService($parametreService);
        return $controller;
    }
}