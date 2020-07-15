<?php

namespace Application\Controller;

use Application\Form\Application\ApplicationForm;
use Application\Form\ApplicationGroupe\ApplicationGroupeForm;
use Application\Service\Application\ApplicationGroupeService;
use Application\Service\Application\ApplicationService;
use Interop\Container\ContainerInterface;

class ApplicationControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ApplicationService $applicationService
         * @var ApplicationGroupeService $applicationGroupeService
         */
        $applicationService = $container->get(ApplicationService::class);
        $applicationGroupeService = $container->get(ApplicationGroupeService::class);

        /**
         * @var ApplicationForm $applicationForm
         * @var ApplicationGroupeForm $applicationGroupeForm
         */
        $applicationForm = $container->get('FormElementManager')->get(ApplicationForm::class);
        $applicationGroupeForm = $container->get('FormElementManager')->get(ApplicationGroupeForm::class);

        /** @var ApplicationController $controller */
        $controller = new ApplicationController();
        $controller->setApplicationService($applicationService);
        $controller->setApplicationGroupeService($applicationGroupeService);
        $controller->setApplicationForm($applicationForm);
        $controller->setApplicationGroupeForm($applicationGroupeForm);
        return $controller;
    }
}