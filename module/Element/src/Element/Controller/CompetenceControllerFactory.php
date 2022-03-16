<?php

namespace Element\Controller;

use Element\Form\SelectionCompetence\SelectionCompetenceForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\FicheMetier\FicheMetierService;
use Element\Form\Competence\CompetenceForm;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\CompetenceTheme\CompetenceThemeService;
use Element\Service\CompetenceType\CompetenceTypeService;
use Element\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class CompetenceControllerFactory
{

    public function __invoke(ContainerInterface $container) : CompetenceController
    {
        /**
         * @var ActiviteService $activiteService
         * @var CompetenceService $competenceService
         * @var NiveauService $maitriseNiveauService
         * @var CompetenceThemeService $competenceThemeService
         * @var CompetenceTypeService $competenceTypeService
         * @var CompetenceElementService $competenceElementService
         * @var FicheMetierService $ficherMetierService
         */
        $activiteService = $container->get(ActiviteService::class);
        $competenceService = $container->get(CompetenceService::class);
        $maitriseNiveauService = $container->get(NiveauService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $ficherMetierService = $container->get(FicheMetierService::class);

        /**
         * @var CompetenceForm $competenceForm
         * @var SelectionCompetenceForm $selectionCompetenceForm
         */
        $competenceForm = $container->get('FormElementManager')->get(CompetenceForm::class);
        $selectionCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);

        /** @var CompetenceController $controller */
        $controller = new CompetenceController();
        $controller->setActiviteService($activiteService);
        $controller->setCompetenceService($competenceService);
        $controller->setNiveauService($maitriseNiveauService);
        $controller->setCompetenceThemeService($competenceThemeService);
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setFicheMetierService($ficherMetierService);
        $controller->setCompetenceForm($competenceForm);
        $controller->setSelectionCompetenceForm($selectionCompetenceForm);
        return $controller;
    }
}