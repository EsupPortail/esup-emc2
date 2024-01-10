<?php

namespace MissionSpecifique\Service\MissionSpecifique;

use Doctrine\ORM\EntityManager;
use MissionSpecifique\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionSpecifiqueServiceFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MissionSpecifiqueService
    {
        /**
         * @var EntityManager $entityManager
         * @var MissionSpecifiqueThemeService $missionSpecifiqueThemeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $missionSpecifiqueThemeService = $container->get(MissionSpecifiqueThemeService::class);


        $service = new MissionSpecifiqueService();
        $service->setObjectManager($entityManager);
        $service->setMissionSpecifiqueThemeService($missionSpecifiqueThemeService);
        return $service;
    }
}