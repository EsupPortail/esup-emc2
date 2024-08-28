<?php

namespace FicheReferentiel\Controller;

use Element\Service\CompetenceType\CompetenceTypeService;
use FicheReferentiel\Form\Importation\ImportationForm;
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
         * @var ImportationForm $importationForm
         */
        $importationForm = $container->get('FormElementManager')->get(ImportationForm::class);

        $controller = new ImporterController();
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setFichierService($fichierService);
        $controller->setImporterService($importerService);
        $controller->setImportationForm($importationForm);
        return $controller;
    }
}