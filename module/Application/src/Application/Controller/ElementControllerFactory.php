<?php

namespace Application\Controller;

use Element\Form\SelectionNiveau\SelectionNiveauForm;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Formation\Service\FormationElement\FormationElementService;
use Interop\Container\ContainerInterface;

class ElementControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ElementController
     */
    public function __invoke(ContainerInterface $container) : ElementController
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
         * @var SelectionNiveauForm $selectionMaitriseForm
         */
        $selectionMaitriseForm = $container->get('FormElementManager')->get(SelectionNiveauForm::class);

        $controller = new ElementController();
        $controller->setApplicationElementService($applicationElementService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setFormationElementService($formationElementService);
        $controller->setSelectionNiveauForm($selectionMaitriseForm);
        return $controller;
    }
}