<?php

namespace Element\Controller;

use Element\Form\CompetenceImportation\CompetenceImportationForm;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Element\Service\CompetenceTheme\CompetenceThemeService;
use Element\Service\CompetenceType\CompetenceTypeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceImporterControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceImporterController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceImporterController
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceReferentielService $competenceReferentielService
         * @var CompetenceThemeService $competenceThemeService
         * @var CompetenceTypeService $competenceTypeService
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceReferentielService = $container->get(CompetenceReferentielService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);

        /** @var CompetenceImportationForm $competenceImportationForm */
        $competenceImportationForm = $container->get('FormElementManager')->get(CompetenceImportationForm::class);

        $controller = new CompetenceImporterController();
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceReferentielService($competenceReferentielService);
        $controller->setCompetenceThemeService($competenceThemeService);
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setCompetenceImportationForm($competenceImportationForm);
        return $controller;
    }
}