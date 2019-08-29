<?php

namespace Application\Controller\Formation;

use Application\Form\Formation\FormationForm;
use Application\Service\Formation\FormationService;
use Zend\Mvc\Controller\ControllerManager;

class FormationControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var FormationService $formationService
         * @var FormationForm $formationForm
         */
        $formationService = $manager->getServiceLocator()->get(FormationService::class);
        $formationForm = $manager->getServiceLocator()->get('FormElementManager')->get(FormationForm::class);

        /** @var FormationController $controller */
        $controller = new FormationController();
        $controller->setFormationService($formationService);
        $controller->setFormationForm($formationForm);
        return $controller;
    }

}