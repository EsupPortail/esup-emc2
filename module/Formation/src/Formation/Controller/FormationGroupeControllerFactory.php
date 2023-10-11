<?php

namespace Formation\Controller;

use Formation\Form\FormationGroupe\FormationGroupeForm;
use Formation\Form\SelectionFormationGroupe\SelectionFormationGroupeForm;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationGroupeControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationGroupeController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationGroupeController
    {
        /**
         * @var FormationService $formationService
         * @var FormationGroupeService $formationGroupeService
         */
        $formationService = $container->get(FormationService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);

        /**
         * @var FormationGroupeForm $formationGroupeForm
         * @var SelectionFormationGroupeForm $selectionFormationGroupeForm
         */
        $formationGroupeForm = $container->get('FormElementManager')->get(FormationGroupeForm::class);
        $selectionFormationGroupeForm = $container->get('FormElementManager')->get(SelectionFormationGroupeForm::class);

        $controller = new FormationGroupeController();
        $controller->setFormationService($formationService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setFormationGroupeForm($formationGroupeForm);
        $controller->setSelectionFormationGroupeForm($selectionFormationGroupeForm);
        return $controller;
    }
}