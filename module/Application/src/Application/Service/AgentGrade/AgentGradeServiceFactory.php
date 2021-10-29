<?php

namespace Application\Service\AgentGrade;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class AgentGradeServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentGradeService
     */
    public function __invoke(ContainerInterface $container) : AgentGradeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new AgentGradeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}