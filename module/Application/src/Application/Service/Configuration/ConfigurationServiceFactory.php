<?php

namespace Application\Service\Configuration;

use Doctrine\ORM\EntityManager;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\CompetenceElement\CompetenceElementService;
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
         * @var CompetenceElementService $competenceElementService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $applicationElementService = $container->get(ApplicationElementService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);

        /** @var ConfigurationService $service */
        $service = new ConfigurationService();
        $service->setEntityManager($entityManager);
        $service->setApplicationElementService($applicationElementService);
        $service->setCompetenceElementService($competenceElementService);
        return $service;
    }
}