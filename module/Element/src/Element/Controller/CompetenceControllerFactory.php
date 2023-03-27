<?php

namespace Element\Controller;

use Element\Form\Competence\CompetenceForm;
use Element\Form\SelectionCompetence\SelectionCompetenceForm;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\CompetenceTheme\CompetenceThemeService;
use Element\Service\CompetenceType\CompetenceTypeService;
use Element\Service\Niveau\NiveauService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @return CompetenceController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CompetenceController
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceThemeService $competenceThemeService
         * @var CompetenceTypeService $competenceTypeService
         * @var CompetenceElementService $competenceElementService
         * @var FicheMetierService $ficherMetierService
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var NiveauService $maitriseNiveauService
         */
        $competenceService = $container->get(CompetenceService::class);
        $maitriseNiveauService = $container->get(NiveauService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $ficherMetierService = $container->get(FicheMetierService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);

        /**
         * @var CompetenceForm $competenceForm
         * @var SelectionCompetenceForm $selectionCompetenceForm
         */
        $competenceForm = $container->get('FormElementManager')->get(CompetenceForm::class);
        $selectionCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);

        $controller = new CompetenceController();
        $controller->setCompetenceService($competenceService);
        $controller->setNiveauService($maitriseNiveauService);
        $controller->setCompetenceThemeService($competenceThemeService);
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setFicheMetierService($ficherMetierService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setCompetenceForm($competenceForm);
        $controller->setSelectionCompetenceForm($selectionCompetenceForm);
        return $controller;
    }
}