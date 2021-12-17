<?php

namespace Application\Service\AgentStageObservation;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class AgentStageObservationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentStageObservationService
     */
    public function __invoke(ContainerInterface $container) : AgentStageObservationService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new AgentStageObservationService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}