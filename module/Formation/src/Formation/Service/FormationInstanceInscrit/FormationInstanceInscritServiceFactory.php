<?php

namespace Formation\Service\FormationInstanceInscrit;

use Application\Service\Structure\StructureService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FormationInstanceInscritServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceInscritService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var StructureService $structureService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $structureService = $container->get(StructureService::class);

        /** @var FormationInstanceInscritService $service */
        $service = new FormationInstanceInscritService();
        $service->setEntityManager($entityManager);
        $service->setStructureService($structureService);
        return $service;
    }
}