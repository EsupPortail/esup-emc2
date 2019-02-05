<?php

namespace Application\Controller\Application;

use Application\Form\Application\ApplicationForm;
use Application\Service\Application\ApplicationService;
use Zend\Mvc\Controller\ControllerManager;

class ApplicationControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var ApplicationService $applicationService
         */
        $applicationService = $manager->getServiceLocator()->get(ApplicationService::class);

        /**
         * @var ApplicationForm $applicationForm
         */
        $applicationForm = $manager->getServiceLocator()->get('FormElementManager')->get(ApplicationForm::class);

        /** @var ApplicationController $controller */
        $controller = new ApplicationController();
        $controller->setApplicationService($applicationService);
        $controller->setApplicationForm($applicationForm);
        return $controller;
    }
}