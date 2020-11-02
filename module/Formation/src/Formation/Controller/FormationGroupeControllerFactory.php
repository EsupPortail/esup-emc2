<?php

namespace Formation\Controller;

use Formation\Form\FormationGroupe\FormationGroupeForm;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Interop\Container\ContainerInterface;

class FormationGroupeControllerFactory
{

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationGroupeService $formationGroupeService
         */
        $formationGroupeService = $container->get(FormationGroupeService::class);

        /**
         * @var FormationGroupeForm $formationGroupeForm
         */
        $formationGroupeForm = $container->get('FormElementManager')->get(FormationGroupeForm::class);

        $controller = new FormationGroupeController();
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setFormationGroupeForm($formationGroupeForm);
        return $controller;
    }
}