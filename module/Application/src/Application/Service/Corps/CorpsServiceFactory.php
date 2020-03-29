<?php

namespace Application\Service\Corps;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class CorpsServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CorpsService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var CorpsService $service */
        $service = new CorpsService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}
