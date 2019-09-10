<?php

namespace Application\Service\Application;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ApplicationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationService
     */
    public function __invoke(ContainerInterface $container) {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ApplicationService $service */
        $service = new ApplicationService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}