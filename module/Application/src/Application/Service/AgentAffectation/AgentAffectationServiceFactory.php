<?php

namespace Application\Service\AgentAffectation;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentAffectationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return AgentAffectationService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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