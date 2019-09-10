<?php

namespace Application\Controller;

use Application\Form\Application\ApplicationForm;
use Application\Service\Application\ApplicationService;
use Interop\Container\ContainerInterface;

class ApplicationControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ApplicationService $applicationService
         */
        $applicationService = $container->get(ApplicationService::class);

        /**
         * @var ApplicationForm $applicationForm
         */
        $applicationForm = $container->get('FormElementManager')->get(ApplicationForm::class);

        /** @var ApplicationController $controller */
        $controller = new ApplicationController();
        $controller->setApplicationService($applicationService);
        $controller->setApplicationForm($applicationForm);
        return $controller;
    }
}