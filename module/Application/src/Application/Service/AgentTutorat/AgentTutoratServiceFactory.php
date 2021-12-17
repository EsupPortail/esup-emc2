<?php

namespace Application\Service\AgentTutorat;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class AgentTutoratServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentTutoratService
     */
    public function __invoke(ContainerInterface $container) : AgentTutoratService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new AgentTutoratService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}