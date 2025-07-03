<?php

namespace FicheMetier\Controller;

use FicheMetier\Form\TendanceType\TendanceTypeForm;
use FicheMetier\Service\TendanceType\TendanceTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class TendanceTypeControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): TendanceTypeController
    {
        /**
         * @var TendanceTypeService $tendanceTypeService
         * @var TendanceTypeForm $tendanceTypeForm
         */
        $tendanceTypeService = $container->get(TendanceTypeService::class);
        $tendanceTypeForm = $container->get('FormElementManager')->get(TendanceTypeForm::class);

        $controller = new TendanceTypeController();
        $controller->setTendanceTypeService($tendanceTypeService);
        $controller->setTendanceTypeForm($tendanceTypeForm);
        return $controller;
    }
}