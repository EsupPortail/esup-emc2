<?php

namespace Formation\Controller;

use Formation\Form\FormationJournee\FormationJourneeForm;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeService;
use Interop\Container\ContainerInterface;

class FormationInstanceJourneeControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceJourneeController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceJourneeService $formationInstanceJourneeService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceJourneeService = $container->get(FormationInstanceJourneeService::class);

        /**
         * @var FormationJourneeForm $formationInstanceJourneeForm
         */
        $formationInstanceJourneeForm = $container->get('FormElementManager')->get(FormationJourneeForm::class);

        $controller = new FormationInstanceJourneeController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceJourneeService($formationInstanceJourneeService);
        $controller->setFormationJourneeForm($formationInstanceJourneeForm);
        return $controller;
    }
}