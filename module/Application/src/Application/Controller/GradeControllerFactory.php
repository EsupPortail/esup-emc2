<?php

namespace Application\Controller;

use Application\Service\Grade\GradeService;
use Interop\Container\ContainerInterface;

class GradeControllerFactory {

    public function __invoke(ContainerInterface $container) : GradeController
    {
        /**
         * @var GradeService $gradeService
         */
        $gradeService = $container->get(GradeService::class);

        $controller = new GradeController();
        $controller->setGradeService($gradeService);
        return $controller;
    }
}