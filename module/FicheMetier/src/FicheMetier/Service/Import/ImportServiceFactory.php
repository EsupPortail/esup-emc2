<?php

namespace FicheMetier\Service\Import;

use Carriere\Service\Categorie\CategorieService;
use Carriere\Service\Correspondance\CorrespondanceService;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Element\Service\Competence\CompetenceService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\Referentiel\ReferentielService;

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
         * @var ReferentielService $competenceReferentielService
         * @var CorrespondanceService $correspondanceService
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         */
        $categorieService = $container->get(CategorieService::class);
        $competenceService = $container->get(CompetenceService::class);
        $referentielService = $container->get(ReferentielService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);

        $service = new ImportService();
        $service->setCategorieService($categorieService);
        $service->setCompetenceService($competenceService);
        $service->setReferentielService($referentielService);
        $service->setCorrespondanceService($correspondanceService);
        $service->setFamilleProfessionnelleService($familleProfessionnelleService);
        return $service;
    }

}