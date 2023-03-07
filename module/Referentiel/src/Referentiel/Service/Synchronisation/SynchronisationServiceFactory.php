<?php

namespace Referentiel\Service\Synchronisation;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\SqlHelper\SqlHelperService;

class SynchronisationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return SynchronisationService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SynchronisationService
    {
        /**
         * @var EntityManager $source
         * @var EntityManager $destination
         * @var SqlHelperService $sqlHelper
         */
        $source = $container->get('doctrine.entitymanager.orm_octopus');
        $destination = $container->get('doctrine.entitymanager.orm_default');
        $entityManagers = [
              'orm_octopus' => $source,
              'orm_default' => $destination,
        ];
        $sqlHelper = $container->get(SqlHelperService::class);

        $configs = $container->get('Config')['synchros'];

        $service = new SynchronisationService();
        $service->setSqlHelperService($sqlHelper);
        $service->setConfigs($configs);
        $service->setEntityManagers($entityManagers);
        return $service;
    }
}