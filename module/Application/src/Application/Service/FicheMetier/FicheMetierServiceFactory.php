<?php

namespace Application\Service\FicheMetier;

use Doctrine\ORM\EntityManager;
use Element\Service\Application\ApplicationService;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;
use UnicaenEtat\Service\Etat\EtatService;

class FicheMetierServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierService
     */
    public function __invoke(ContainerInterface $container) : FicheMetierService
    {
        /**
         * @var EntityManager $entityManager
         * @var ApplicationService $applicationService
         * @var ApplicationElementService $applicationElementService
         * @var CompetenceService $competenceService
         * @var CompetenceElementService $competenceElementService
         * @var DomaineService $domaineService
         * @var EtatService $etatService
         * @var FormationService $formationService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $applicationService = $container->get(ApplicationService::class);
        $applicationElementService = $container->get(ApplicationElementService::class);
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $domaineService = $container->get(DomaineService::class);
        $etatService = $container->get(EtatService::class);
        $formationService = $container->get(FormationService::class);

        /** @var FicheMetierService $service */
        $service = new FicheMetierService();
        $service->setEntityManager($entityManager);
        $service->setApplicationService($applicationService);
        $service->setApplicationElementService($applicationElementService);
        $service->setCompetenceService($competenceService);
        $service->setCompetenceElementService($competenceElementService);
        $service->setDomaineService($domaineService);
        $service->setEtatService($etatService);
        $service->setFormationService($formationService);

        return $service;
    }
}