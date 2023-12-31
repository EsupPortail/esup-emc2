<?php

namespace Element\Service\ApplicationElement;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ApplicationElementServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return ApplicationElementService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ApplicationElementService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new ApplicationElementService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}