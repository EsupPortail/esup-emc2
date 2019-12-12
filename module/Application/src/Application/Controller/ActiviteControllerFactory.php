<?php

namespace Application\Controller;

use Application\Form\Activite\ActiviteForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\ActiviteDescription\ActiviteDescriptionService;
use Interop\Container\ContainerInterface;

class ActiviteControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ActiviteService $activiteService
         * @var ActiviteDescriptionService $activiteDescriptionService
         */
        $activiteService = $container->get(ActiviteService::class);
        $activiteDescriptionService = $container->get(ActiviteDescriptionService::class);

        /**
         * @var ActiviteForm $activiteForm
         */
        $activiteForm = $container->get('FormElementManager')->get(ActiviteForm::class);


        /** @var ActiviteController $controller */
        $controller = new ActiviteController();
        $controller->setActiviteService($activiteService);
        $controller->setActiviteDescriptionService($activiteDescriptionService);
        $controller->setActiviteForm($activiteForm);
        return $controller;
    }
}