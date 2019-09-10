<?php

namespace Application\Controller;

use Application\Form\Activite\ActiviteForm;
use Application\Service\Activite\ActiviteService;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\ControllerManager;

class ActiviteControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ActiviteService $activiteService
         */
        $activiteService = $container->get(ActiviteService::class);

        /**
         * @var ActiviteForm $activiteForm
         */
        $activiteForm = $container->get('FormElementManager')->get(ActiviteForm::class);


        /** @var ActiviteController $controller */
        $controller = new ActiviteController();
        $controller->setActiviteService($activiteService);
        $controller->setActiviteForm($activiteForm);
        return $controller;
    }
}