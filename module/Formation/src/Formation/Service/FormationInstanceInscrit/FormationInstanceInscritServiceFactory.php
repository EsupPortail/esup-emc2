<?php

namespace Formation\Service\FormationInstanceInscrit;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

class FormationInstanceInscritServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceInscritService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationInstanceInscritService
    {
        /**
         * @var EntityManager $entityManager
         * @var StructureService $structureService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $structureService = $container->get(StructureService::class);

        $service = new FormationInstanceInscritService();
        $service->setEntityManager($entityManager);
        $service->setStructureService($structureService);
        return $service;
    }
}