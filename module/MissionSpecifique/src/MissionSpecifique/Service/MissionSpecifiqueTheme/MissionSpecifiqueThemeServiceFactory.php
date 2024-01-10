<?php

namespace MissionSpecifique\Service\MissionSpecifiqueTheme;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionSpecifiqueThemeServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MissionSpecifiqueThemeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new MissionSpecifiqueThemeService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}