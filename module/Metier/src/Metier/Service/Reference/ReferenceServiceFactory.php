<?php

namespace Metier\Service\Reference;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ReferenceServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferenceService
     */
    public function __invoke(ContainerInterface $container) : ReferenceService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ReferenceService $service */
        $service = new ReferenceService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}