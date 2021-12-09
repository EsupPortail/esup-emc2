<?php

namespace Application\Service\Evenement;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenDbImport\Service\SynchroService;
use UnicaenEvenement\Service\Type\TypeService;

class SynchroOctopusServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return SynchroOctopusService
     */
    public function __invoke(ContainerInterface $container) : SynchroOctopusService
    {
        /**
         * @var EntityManager $entityManager
         * @var TypeService $typeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $synchroService = $container->get(SynchroService::class);
        $typeService = $container->get(TypeService::class);

        $service = new SynchroOctopusService();

        $service->setEntityManager($entityManager);
        $service->setSynchroService($synchroService);
        $service->setTypeService($typeService);
        return $service;
    }
}