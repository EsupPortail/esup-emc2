<?php

namespace Application\Service\Configuration;

use Doctrine\ORM\EntityManager;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\HasApplicationCollection\HasApplicationCollectionService;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

;

class ConfigurationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ConfigurationService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) :ConfigurationService
    {
        /**
         * @var EntityManager $entityManager
         * @var ApplicationElementService $applicationElementService
         * @var HasApplicationCollectionService $hasApplicationCollectionService
         * @var CompetenceElementService $competenceElementService
         * @var HasCompetenceCollectionService $hasCompetenceCollectionService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $applicationElementService = $container->get(ApplicationElementService::class);
        $hasApplicationCollectionService = $container->get(HasApplicationCollectionService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $hasCompetenceCollectionService = $container->get(HasCompetenceCollectionService::class);

        /** @var ConfigurationService $service */
        $service = new ConfigurationService();
        $service->setEntityManager($entityManager);
        $service->setApplicationElementService($applicationElementService);
        $service->setHasApplicationCollectionService($hasApplicationCollectionService);
        $service->setCompetenceElementService($competenceElementService);
        $service->setHasCompetenceCollectionService($hasCompetenceCollectionService);
        return $service;
    }
}