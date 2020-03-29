<?php

namespace Application\Controller;

use Application\Service\Corps\CorpsService;
use Application\Service\Correspondance\CorrespondanceService;
use Application\Service\Grade\GradeService;
use Interop\Container\ContainerInterface;

class CorpsControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CorpsController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CorpsService $corpsService
         * @var CorrespondanceService $correspondanceService
         * @var GradeService $gradeService
         */
        $corpsService = $container->get(CorpsService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $gradeService = $container->get(GradeService::class);

        /** @var CorpsController $controller */
        $controller = new CorpsController();
        $controller->setCorpsService($corpsService);
        $controller->setCorrespondanceService($correspondanceService);
        $controller->setGradeService($gradeService);
        return $controller;
    }
}