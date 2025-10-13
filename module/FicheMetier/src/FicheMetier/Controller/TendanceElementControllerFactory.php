<?php

namespace FicheMetier\Controller;

use FicheMetier\Form\TendanceElement\TendanceElementForm;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\TendanceElement\TendanceElementService;
use FicheMetier\Service\TendanceType\TendanceTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class TendanceElementControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): TendanceElementController
    {
        /**
         * @var FicheMetierService $ficheMetierService
         * @var TendanceElementService $tendanceElementService
         * @var TendanceTypeService $tendanceTypeService
         */
        $ficheMetierService = $container->get(FicheMetierService::class);
        $tendanceElementService = $container->get(TendanceElementService::class);
        $tendanceTypeService = $container->get(TendanceTypeService::class);

        /**
         * @var TendanceElementForm $tendanceElementForm
         */
        $tendanceElementForm = $container->get('FormElementManager')->get(TendanceElementForm::class);

        $controller = new TendanceElementController();
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setTendanceElementService($tendanceElementService);
        $controller->setTendanceTypeService($tendanceTypeService);
        $controller->setTendanceElementForm($tendanceElementForm);
        return $controller;
    }

}