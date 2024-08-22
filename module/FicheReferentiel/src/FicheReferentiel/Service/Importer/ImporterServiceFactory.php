<?php

namespace FicheReferentiel\Service\Importer;

use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Element\Service\CompetenceType\CompetenceTypeService;
use FicheReferentiel\Service\FicheReferentiel\FicheReferentielService;
use Metier\Service\Metier\MetierService;
use Metier\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ImporterServiceFactory {

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ImporterService
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceElementService $competenceElementService
         * @var CompetenceReferentielService $competenceReferentielService
         * @var CompetenceTypeService $competenceTypeService
         * @var FicheReferentielService $ficheReferentielService
         * @var MetierService $metierService
         * @var ReferentielService $referentielService
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $competenceReferentielService = $container->get(CompetenceReferentielService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $ficheReferentielService = $container->get(FicheReferentielService::class);
        $metierService = $container->get(MetierService::class);
        $referentielService = $container->get(ReferentielService::class);

        $service = new ImporterService();
        $service->setCompetenceService($competenceService);
        $service->setCompetenceElementService($competenceElementService);
        $service->setCompetenceReferentielService($competenceReferentielService);
        $service->setCompetenceTypeService($competenceTypeService);
        $service->setFicheReferentielService($ficheReferentielService);
        $service->setMetierService($metierService);
        $service->setReferentielService($referentielService);
        return $service;
    }
}