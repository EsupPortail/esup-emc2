<?php

namespace Formation\Service\ActionType;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ActionTypeServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ActionTypeService
    {
        /** @var EntityManager  $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new ActionTypeService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}