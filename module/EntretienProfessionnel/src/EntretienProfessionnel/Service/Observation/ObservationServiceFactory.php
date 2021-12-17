<?php

namespace EntretienProfessionnel\Service\Observation;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ObservationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ObservationService
     */
    public function __invoke(ContainerInterface $container) : ObservationService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new ObservationService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}