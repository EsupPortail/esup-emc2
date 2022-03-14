<?php

namespace Application\Service\FicheProfil;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;

class FicheProfilServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheProfilService
     */
    public function __invoke(ContainerInterface $container) : FicheProfilService
    {
        /**
         * @var EntityManager $entityManager
         * @var StructureService $structureService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $structureService = $container->get(StructureService::class);

        $service = new FicheProfilService();
        $service->setEntityManager($entityManager);
        $service->setStructureService($structureService);
        return $service;
    }
}