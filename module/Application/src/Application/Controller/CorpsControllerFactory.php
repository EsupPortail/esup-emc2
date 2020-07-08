<?php

namespace Application\Controller;

use Application\Service\Categorie\CategorieService;
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
         * @var CategorieService $categorieService
         * @var CorpsService $corpsService
         * @var CorrespondanceService $correspondanceService
         * @var GradeService $gradeService
         */
        $categorieService = $container->get(CategorieService::class);
        $corpsService = $container->get(CorpsService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $gradeService = $container->get(GradeService::class);

        /** @var CorpsController $controller */
        $controller = new CorpsController();
        $controller->setCategorieService($categorieService);
        $controller->setCorpsService($corpsService);
        $controller->setCorrespondanceService($correspondanceService);
        $controller->setGradeService($gradeService);
        return $controller;
    }
}