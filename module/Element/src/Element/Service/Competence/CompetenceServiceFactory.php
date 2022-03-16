<?php

namespace Element\Service\Competence;

use Element\Service\CompetenceTheme\CompetenceThemeService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class CompetenceServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceService
     */
    public function __invoke(ContainerInterface $container) : CompetenceService
    {
        /**
         * @var EntityManager $entityManager
         * @var CompetenceThemeService $competenceThemeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $competenceThemeService = $container->get(CompetenceThemeService::class);

        /** @var CompetenceService $service */
        $service = new CompetenceService();
        $service->setEntityManager($entityManager);
        $service->setCompetenceThemeService($competenceThemeService);
        return $service;
    }
}