<?php

namespace Application\Service\ParcoursDeFormation;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ParcoursDeFormationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ParcoursDeFormationService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ParcoursDeFormationService $service */
        $service = new ParcoursDeFormationService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}