<?php

namespace MissionSpecifique\Service\MissionSpecifiqueType;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionSpecifiqueTypeServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return MissionSpecifiqueTypeService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MissionSpecifiqueTypeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new MissionSpecifiqueTypeService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}