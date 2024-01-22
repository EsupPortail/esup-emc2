<?php

namespace Application\Service\Evenement;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEvenement\Service\Type\TypeService;
use UnicaenSynchro\Service\Synchronisation\SynchronisationService;

class SynchroOctopusServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return SynchroOctopusService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SynchroOctopusService
    {
        /**
         * @var EntityManager $entityManager
         * @var TypeService $typeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $synchroService = $container->get(SynchronisationService::class);
        $typeService = $container->get(TypeService::class);

        $service = new SynchroOctopusService();

        $service->setEntityManager($entityManager);
        $service->setSynchronisationService($synchroService);
        $service->setTypeService($typeService);
        return $service;
    }
}