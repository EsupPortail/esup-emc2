<?php

namespace Formation\Controller;

use Formation\Form\Seance\SeanceForm;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\Seance\SeanceService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SeanceControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return SeanceController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SeanceController
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var SeanceService $seanceService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $seanceService = $container->get(SeanceService::class);

        /**
         * @var SeanceForm $seanceForm
         */
        $seanceForm = $container->get('FormElementManager')->get(SeanceForm::class);

        $controller = new SeanceController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setSeanceService($seanceService);
        $controller->setSeanceForm($seanceForm);
        return $controller;
    }
}