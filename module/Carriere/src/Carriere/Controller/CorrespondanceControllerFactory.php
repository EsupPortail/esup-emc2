<?php

namespace Carriere\Controller;

use Application\Service\AgentGrade\AgentGradeService;
use Carriere\Service\Correspondance\CorrespondanceService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class CorrespondanceControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CorrespondanceController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CorrespondanceController
    {
        /**
         * @var AgentGradeService $agentGradeService
         * @var CorrespondanceService $correspondanceService
         * @var ParametreService $parametreService
         */
        $agentGradeService = $container->get(AgentGradeService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $parametreService = $container->get(ParametreService::class);

        $controller = new CorrespondanceController();
        $controller->setAgentGradeService($agentGradeService);
        $controller->setCorrespondanceService($correspondanceService);
        $controller->setParametreService($parametreService);
        return $controller;
    }
}