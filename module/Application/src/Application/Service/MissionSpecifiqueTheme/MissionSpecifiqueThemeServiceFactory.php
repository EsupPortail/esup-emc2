<?php

namespace Application\Service\MissionSpecifiqueTheme;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueThemeServiceFactory {

    public function __invoke(ContainerInterface $container) : MissionSpecifiqueThemeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new MissionSpecifiqueThemeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}