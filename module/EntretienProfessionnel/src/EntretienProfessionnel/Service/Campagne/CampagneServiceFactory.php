<?php

namespace EntretienProfessionnel\Service\Campagne;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class CampagneServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CampagneService
     */
    public function __invoke(ContainerInterface $container) : CampagneService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new CampagneService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}