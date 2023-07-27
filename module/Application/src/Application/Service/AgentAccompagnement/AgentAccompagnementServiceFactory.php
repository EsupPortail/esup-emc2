<?php

namespace Application\Service\AgentAccompagnement;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentAccompagnementServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentAccompagnementService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentAccompagnementService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new AgentAccompagnementService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}