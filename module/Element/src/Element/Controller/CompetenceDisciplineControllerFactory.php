<?php

namespace Element\Controller;

use Element\Form\CompetenceDiscipline\CompetenceDisciplineForm;
use Element\Service\CompetenceDiscipline\CompetenceDisciplineService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceDisciplineControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceDisciplineController
    {
        /**
         * @var CompetenceDisciplineService $competenceDisciplineService
         * @var FicheMetierService $ficheMetierService
         * @var CompetenceDisciplineForm $competenceDisciplineForm
         */
        $competenceDisciplineService = $container->get(CompetenceDisciplineService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $competenceDisciplineForm = $container->get('FormElementManager')->get(CompetenceDisciplineForm::class);

        $controller = new CompetenceDisciplineController();
        $controller->setCompetenceDisciplineService($competenceDisciplineService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setCompetenceDisciplineForm($competenceDisciplineForm);
        return $controller;
    }
}