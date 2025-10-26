<?php

namespace Element\Service\Competence;

use Element\Service\CompetenceDiscipline\CompetenceDisciplineService;
use Element\Service\CompetenceTheme\CompetenceThemeService;
use Doctrine\ORM\EntityManager;
use Element\Service\CompetenceType\CompetenceTypeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceService
    {
        /**
         * @var EntityManager $entityManager
         * @var CompetenceDisciplineService $competenceDisciplineService
         * @var CompetenceThemeService $competenceThemeService
         * @var CompetenceTypeService $competenceTypeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $competenceDisciplineService = $container->get(CompetenceDisciplineService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);

        $service = new CompetenceService();
        $service->setObjectManager($entityManager);
        $service->setCompetenceDisciplineService($competenceDisciplineService);
        $service->setCompetenceThemeService($competenceThemeService);
        $service->setCompetenceTypeService($competenceTypeService);
        return $service;
    }
}