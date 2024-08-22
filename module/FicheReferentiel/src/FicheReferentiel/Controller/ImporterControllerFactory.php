<?php

namespace FicheReferentiel\Controller;

use Element\Service\CompetenceType\CompetenceTypeService;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationForm;
use FicheReferentiel\Service\Importer\ImporterService;
use Fichier\Service\Fichier\FichierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ImporterControllerFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ImporterController
    {
        /**
         * @var CompetenceTypeService $competenceTypeService
         * @var FichierService $fichierService
         * @var ImporterService $importerService
         */
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $fichierService = $container->get(FichierService::class);
        $importerService = $container->get(ImporterService::class);

        /**
         * @var FicheMetierImportationForm $importationForm
         */
        $importationForm = $container->get('FormElementManager')->get(FicheMetierImportationForm::class);

        $controller = new ImporterController();
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setFichierService($fichierService);
        $controller->setImporterService($importerService);
        $controller->setFicheMetierImportationForm($importationForm);
        return $controller;
    }
}