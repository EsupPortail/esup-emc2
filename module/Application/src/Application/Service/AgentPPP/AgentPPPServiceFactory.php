<?php

namespace Application\Service\AgentPPP;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentPPPServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentPPPService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentPPPService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new AgentPPPService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}