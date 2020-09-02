<?php

namespace Application\Controller;

use Application\Form\FormationJournee\FormationJourneeForm;
use Application\Service\Formation\FormationService;
use Application\Service\FormationInstance\FormationInstanceService;
use Interop\Container\ContainerInterface;

class FormationInstanceControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         * @var FormationInstanceService $formationInstanceService
         */
        $formationService = $container->get(FormationService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);

        /**
         * @var FormationJourneeForm $formationJourneeForm
         */
        $formationJourneeForm = $container->get('FormElementManager')->get(FormationJourneeForm::class);

        /** @var FormationInstanceController $controller */
        $controller = new FormationInstanceController();
        $controller->setFormationService($formationService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationJourneeForm($formationJourneeForm);
        return $controller;
    }

}