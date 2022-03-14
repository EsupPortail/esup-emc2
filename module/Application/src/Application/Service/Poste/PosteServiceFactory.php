<?php

namespace Application\Service\Poste;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;

class PosteServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return PosteService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var StructureService $structureService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $structureService = $container->get(StructureService::class);


        /** @var PosteService $service */
        $service = new PosteService();
        $service->setEntityManager($entityManager);
        $service->setStructureService($structureService);

        return $service;
    }
}