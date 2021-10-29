<?php

namespace Application\Service\AgentAffectation;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class AgentAffectationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return AgentAffectationService
     */
    public function __invoke(ContainerInterface $container): AgentAffectationService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new AgentAffectationService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}