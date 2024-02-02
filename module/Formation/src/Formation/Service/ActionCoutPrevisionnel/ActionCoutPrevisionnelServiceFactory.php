<?php

namespace Formation\Service\ActionCoutPrevisionnel;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ActionCoutPrevisionnelServiceFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ActionCoutPrevisionnelService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new ActionCoutPrevisionnelService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}