<?php

namespace Element\Service\CompetenceTheme;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class CompetenceThemeServiceFactory {

    public function __invoke(ContainerInterface $container) : CompetenceThemeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new CompetenceThemeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}