<?php

namespace Application\Service\ActivitesDescriptionsRetirees;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ActivitesDescriptionsRetireesServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ActivitesDescriptionsRetireesService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new ActivitesDescriptionsRetireesService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}