<?php

namespace Application\Service\StructureAgentForce;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class StructureAgentForceServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return StructureAgentForceService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new StructureAgentForceService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}