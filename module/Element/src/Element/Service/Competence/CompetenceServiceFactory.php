<?php

namespace Element\Service\Competence;

use Element\Service\CompetenceTheme\CompetenceThemeService;
use Doctrine\ORM\EntityManager;
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
         * @var CompetenceThemeService $competenceThemeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $competenceThemeService = $container->get(CompetenceThemeService::class);

        $service = new CompetenceService();
        $service->setEntityManager($entityManager);
        $service->setCompetenceThemeService($competenceThemeService);
        return $service;
    }
}