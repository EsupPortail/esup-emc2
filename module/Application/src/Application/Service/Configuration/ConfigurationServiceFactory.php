<?php

namespace Application\Service\Configuration;

use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Application\Service\FicheMetier\FicheMetierService;
use Element\Service\HasApplicationCollection\HasApplicationCollectionService;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

;

class ConfigurationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ConfigurationService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var ApplicationElementService $applicationElementService
         * @var HasApplicationCollectionService $hasApplicationCollectionService
         * @var CompetenceElementService $competenceElementService
         * @var HasCompetenceCollectionService $hasCompetenceCollectionService
         * @var FicheMetierService $ficheMetierService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $applicationElementService = $container->get(ApplicationElementService::class);
        $hasApplicationCollectionService = $container->get(HasApplicationCollectionService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $hasCompetenceCollectionService = $container->get(HasCompetenceCollectionService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);

        /** @var ConfigurationService $service */
        $service = new ConfigurationService();
        $service->setEntityManager($entityManager);
        $service->setApplicationElementService($applicationElementService);
        $service->setHasApplicationCollectionService($hasApplicationCollectionService);
        $service->setCompetenceElementService($competenceElementService);
        $service->setHasCompetenceCollectionService($hasCompetenceCollectionService);
        $service->setFicheMetierService($ficheMetierService);
        return $service;
    }
}