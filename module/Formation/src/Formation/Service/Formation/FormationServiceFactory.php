<?php

namespace Formation\Service\Formation;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FormationService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var FormationService $service */
        $service = new FormationService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}
