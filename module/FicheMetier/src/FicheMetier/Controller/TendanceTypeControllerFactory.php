<?php

namespace FicheMetier\Controller;

use FicheMetier\Form\TendanceType\TendanceTypeForm;
use FicheMetier\Service\TendanceType\TendanceTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class TendanceTypeControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): TendanceTypeController
    {
        /**
         * @var ParametreService $parametreService
         * @var TendanceTypeService $tendanceTypeService
         * @var TendanceTypeForm $tendanceTypeForm
         */
        $parametreService = $container->get(ParametreService::class);
        $tendanceTypeService = $container->get(TendanceTypeService::class);
        $tendanceTypeForm = $container->get('FormElementManager')->get(TendanceTypeForm::class);

        $controller = new TendanceTypeController();
        $controller->setParametreService($parametreService);
        $controller->setTendanceTypeService($tendanceTypeService);
        $controller->setTendanceTypeForm($tendanceTypeForm);
        return $controller;
    }
}