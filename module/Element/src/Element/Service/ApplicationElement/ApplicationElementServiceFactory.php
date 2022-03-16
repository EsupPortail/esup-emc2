<?php

namespace Element\Service\ApplicationElement;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ApplicationElementServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationElementService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new ApplicationElementService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}