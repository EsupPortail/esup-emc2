<?php

namespace Application\Controller\Activite;

use Application\Form\Activite\ActiviteForm;
use Application\Service\Activite\ActiviteService;
use Zend\Mvc\Controller\ControllerManager;

class ActiviteControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var ActiviteService $activiteService
         */
        $activiteService = $manager->getServiceLocator()->get(ActiviteService::class);

        /**
         * @var ActiviteForm $activiteForm
         */
        $activiteForm = $manager->getServiceLocator()->get('FormElementManager')->get(ActiviteForm::class);


        /** @var ActiviteController $controller */
        $controller = new ActiviteController();
        $controller->setActiviteService($activiteService);
        $controller->setActiviteForm($activiteForm);
        return $controller;
    }
}