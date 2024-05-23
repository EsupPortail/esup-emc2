<?php

namespace Structure\Service\Observateur;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

class ObservateurServiceFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservateurService
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $structureService = $container->get(StructureService::class);

        $service = new ObservateurService();
        $service->setObjectManager($entityManager);
        $service->setStructureService($structureService);
        return $service;
    }

}