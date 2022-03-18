<?php

namespace Application\Service\MissionSpecifique;

use Application\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueServiceFactory {
    /**
     * @param ContainerInterface $container
     * @return MissionSpecifiqueService
     */
    public function __invoke(ContainerInterface $container) : MissionSpecifiqueService
    {
        /**
         * @var EntityManager $entityManager
         * @var MissionSpecifiqueThemeService $missionSpecifiqueThemeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $missionSpecifiqueThemeService = $container->get(MissionSpecifiqueThemeService::class);


        /** @var MissionSpecifiqueService $service */
        $service = new MissionSpecifiqueService();
        $service->setEntityManager($entityManager);
        $service->setMissionSpecifiqueThemeService($missionSpecifiqueThemeService);
        return $service;
    }
}