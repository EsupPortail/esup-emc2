<?php

namespace Formation\Controller;

use Formation\Form\FormationGroupe\FormationGroupeForm;
use Formation\Form\SelectionFormationGroupe\SelectionFormationGroupeForm;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenDbImport\Entity\Db\Service\Source\SourceService;

class FormationGroupeControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationGroupeController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FormationGroupeController
    {
        /**
         * @var SourceService $sourceService
         * @var FormationService $formationService
         * @var FormationGroupeService $formationGroupeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sourceService = $container->get(SourceService::class);
        $sourceService->setEntityManager($entityManager);
        $formationService = $container->get(FormationService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);

        /**
         * @var FormationGroupeForm $formationGroupeForm
         * @var SelectionFormationGroupeForm $selectionFormationGroupeForm
         */
        $formationGroupeForm = $container->get('FormElementManager')->get(FormationGroupeForm::class);
        $selectionFormationGroupeForm = $container->get('FormElementManager')->get(SelectionFormationGroupeForm::class);

        $controller = new FormationGroupeController();
        $controller->setSourceService($sourceService);
        $controller->setFormationService($formationService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setFormationGroupeForm($formationGroupeForm);
        $controller->setSelectionFormationGroupeForm($selectionFormationGroupeForm);
        return $controller;
    }
}