<?php

namespace Formation\Controller;

use Application\Service\FormationInstance\FormationInstanceService;
use Formation\Form\FormationInstanceFormateur\FormationInstanceFormateurForm;
use Formation\Service\FormationInstanceFormateur\FormationInstanceFormateurService;
use Interop\Container\ContainerInterface;

class FormationInstanceFormateurControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceFormateurController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceFormateurService $formationInstanceFormateurService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceFormateurService = $container->get(FormationInstanceFormateurService::class);

        /**
         * @var FormationInstanceFormateurForm $formationInstanceFormateurForm
         */
        $formationInstanceFormateurForm = $container->get('FormElementManager')->get(FormationInstanceFormateurForm::class);

        $controller = new FormationInstanceFormateurController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceFormateurService($formationInstanceFormateurService);
        $controller->setFormationInstanceFormateurForm($formationInstanceFormateurForm);
        return $controller;
    }
}