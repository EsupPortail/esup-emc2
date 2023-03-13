<?php

namespace Application\Service\AgentPoste;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentPosteServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentPosteService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentPosteService
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new AgentPosteService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}