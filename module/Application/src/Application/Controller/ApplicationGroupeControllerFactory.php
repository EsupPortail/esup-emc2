<?php

namespace Application\Controller;

use Application\Form\ApplicationGroupe\ApplicationGroupeForm;
use Application\Service\Application\ApplicationGroupeService;
use Interop\Container\ContainerInterface;

class ApplicationGroupeControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationGroupeController
     */
    public function __invoke(ContainerInterface $container) : ApplicationGroupeController
    {
        /**
         * @var ApplicationGroupeService $applicationGroupeService
         */
        $applicationGroupeService = $container->get(ApplicationGroupeService::class);

        /**
         * @var ApplicationGroupeForm $applicationGroupeForm
         */
        $applicationGroupeForm = $container->get('FormElementManager')->get(ApplicationGroupeForm::class);

        $controller = new ApplicationGroupeController();
        $controller->setApplicationGroupeService($applicationGroupeService);
        $controller->setApplicationGroupeForm($applicationGroupeForm);
        return $controller;
    }

}