<?php

namespace FicheMetier\Service\Import;

use Carriere\Service\Categorie\CategorieService;
use Carriere\Service\Correspondance\CorrespondanceService;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ImportServiceFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ImportService
    {
        /**
         * @var CategorieService $categorieService
         * @var CompetenceService $competenceService
         * @var CompetenceReferentielService $competenceReferentielService
         * @var CorrespondanceService $correspondanceService
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         */
        $categorieService = $container->get(CategorieService::class);
        $competenceService = $container->get(CompetenceService::class);
        $competenceReferentielService = $container->get(CompetenceReferentielService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);

        $service = new ImportService();
        $service->setCategorieService($categorieService);
        $service->setCompetenceService($competenceService);
        $service->setCompetenceReferentielService($competenceReferentielService);
        $service->setCorrespondanceService($correspondanceService);
        $service->setFamilleProfessionnelleService($familleProfessionnelleService);
        return $service;
    }
    
}