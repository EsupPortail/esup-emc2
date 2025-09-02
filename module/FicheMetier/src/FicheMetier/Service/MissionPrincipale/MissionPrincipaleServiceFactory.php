<?php

namespace FicheMetier\Service\MissionPrincipale;

use Carriere\Service\Niveau\NiveauService;
use Doctrine\ORM\EntityManager;
use Element\Service\Application\ApplicationService;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionPrincipaleServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MissionPrincipaleService
    {
        /**
         * @var EntityManager $entityManager
         * @var ApplicationService $applicationService
         * @var ApplicationElementService $applicationElementService
         * @var CompetenceService $competenceService
         * @var CompetenceElementService $competenceElementService
         * @var CompetenceReferentielService $competenceReferentielService
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         * @var NiveauService $niveauService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $applicationService = $container->get(ApplicationService::class);
        $applicationElementService = $container->get(ApplicationElementService::class);
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $competenceReferentielService = $container->get(CompetenceReferentielService::class);
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);
        $niveauService = $container->get(NiveauService::class);

        $service = new MissionPrincipaleService();
        $service->setObjectManager($entityManager);
        $service->setApplicationService($applicationService);
        $service->setApplicationElementService($applicationElementService);;
        $service->setCompetenceService($competenceService);
        $service->setCompetenceElementService($competenceElementService);
        $service->setCompetenceReferentielService($competenceReferentielService);
        $service->setFamilleProfessionnelleService($familleProfessionnelleService);
        $service->setNiveauService($niveauService);
        return $service;
    }
}