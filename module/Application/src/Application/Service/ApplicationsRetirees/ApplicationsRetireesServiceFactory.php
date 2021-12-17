<?php

namespace Application\Service\ApplicationsRetirees;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ApplicationsRetireesServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationsRetireesService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ApplicationsRetireesService $service */
        $service = new ApplicationsRetireesService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}