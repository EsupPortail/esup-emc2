<?php

namespace Element\Controller;

use Carriere\Service\Corps\CorpsService;
use Carriere\Service\Grade\GradeService;
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
use Structure\Service\Structure\StructureService;

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
         * @var CorpsService $corpsService
         * @var FicheMetierService $ficherMetierService
         * @var GradeService $gradeService
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var NiveauService $maitriseNiveauService
         * @var StructureService $structureService
         */
        $competenceService = $container->get(CompetenceService::class);
        $maitriseNiveauService = $container->get(NiveauService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $corpsService = $container->get(CorpsService::class);
        $ficherMetierService = $container->get(FicheMetierService::class);
        $gradeService = $container->get(GradeService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var CompetenceForm $competenceForm
         * @var SelectionCompetenceForm $selectionCompetenceForm
         */
        $competenceForm = $container->get('FormElementManager')->get(CompetenceForm::class);
        $selectionCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);

        $controller = new CompetenceController();
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceThemeService($competenceThemeService);
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setCorpsService($corpsService);
        $controller->setFicheMetierService($ficherMetierService);
        $controller->setGradeService($gradeService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setNiveauService($maitriseNiveauService);
        $controller->setStructureService($structureService);
        $controller->setCompetenceForm($competenceForm);
        $controller->setSelectionCompetenceForm($selectionCompetenceForm);
        return $controller;
    }
}