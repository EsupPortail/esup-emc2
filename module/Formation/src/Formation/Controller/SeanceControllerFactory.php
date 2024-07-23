<?php

namespace Formation\Controller;

use Formation\Form\Seance\SeanceForm;
use Formation\Service\Seance\SeanceService;
use Formation\Service\Session\SessionService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SeanceControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SeanceController
    {
        /**
         * @var SeanceService $seanceService
         * @var SessionService $sessionService
         */
        $seanceService = $container->get(SeanceService::class);
        $sessionService = $container->get(SessionService::class);

        /**
         * @var SeanceForm $seanceForm
         */
        $seanceForm = $container->get('FormElementManager')->get(SeanceForm::class);

        $controller = new SeanceController();
        $controller->setSeanceService($seanceService);
        $controller->setSessionService($sessionService);
        $controller->setSeanceForm($seanceForm);
        return $controller;
    }
}