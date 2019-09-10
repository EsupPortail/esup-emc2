<?php

namespace Application\Controller;

use Application\Form\Formation\FormationForm;
use Application\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class FormationControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         * @var FormationForm $formationForm
         */
        $formationService = $container->get(FormationService::class);
        $formationForm = $container->get('FormElementManager')->get(FormationForm::class);

        /** @var FormationController $controller */
        $controller = new FormationController();
        $controller->setFormationService($formationService);
        $controller->setFormationForm($formationForm);
        return $controller;
    }

}