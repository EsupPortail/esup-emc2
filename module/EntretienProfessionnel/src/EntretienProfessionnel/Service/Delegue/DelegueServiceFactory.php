<?php

namespace EntretienProfessionnel\Service\Delegue;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class DelegueServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return DelegueService
     */
    public function __invoke(ContainerInterface $container) : DelegueService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new DelegueService();
        $service->setEntityManager($entityManager);
        return $service;
    }

}