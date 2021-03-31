<?php

namespace Application\Controller;

use Application\Form\SelectionCompetenceMaitrise\SelectionCompetenceMaitriseForm;
use Application\Service\ApplicationElement\ApplicationElementService;
use Application\Service\CompetenceElement\CompetenceElementService;
use Formation\Service\FormationElement\FormationElementService;
use Interop\Container\ContainerInterface;

class ElementControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ElementController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ApplicationElementService $applicationElementService
         * @var CompetenceElementService $competenceElementService
         * @var FormationElementService $formationElementService
         */
        $applicationElementService = $container->get(ApplicationElementService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $formationElementService = $container->get(FormationElementService::class);

        /**
         * @var SelectionCompetenceMaitriseForm $selectionMaitriseForm
         */
        $selectionMaitriseForm = $container->get('FormElementManager')->get(SelectionCompetenceMaitriseForm::class);

        $controller = new ElementController();
        $controller->setApplicationElementService($applicationElementService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setFormationElementService($formationElementService);
        $controller->setSelectionCompetenceMaitriseForm($selectionMaitriseForm);
        return $controller;
    }
}