<?php

namespace Element\Form\Competence;

use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceDiscipline\CompetenceDisciplineService;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Element\Service\CompetenceTheme\CompetenceThemeService;
use Element\Service\CompetenceType\CompetenceTypeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceHydrator
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceDisciplineService $competenceDisciplineService
         * @var CompetenceReferentielService $competenceReferentielService
         * @var CompetenceThemeService $competenceThemeService
         * @var CompetenceTypeService $competenceTypeService
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceDisciplineService = $container->get(CompetenceDisciplineService::class);
        $competenceReferentielService = $container->get(CompetenceReferentielService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);

        $hydrator = new CompetenceHydrator();
        $hydrator->setCompetenceService($competenceService);
        $hydrator->setCompetenceDisciplineService($competenceDisciplineService);
        $hydrator->setCompetenceReferentielService($competenceReferentielService);
        $hydrator->setCompetenceThemeService($competenceThemeService);
        $hydrator->setCompetenceTypeService($competenceTypeService);
        return $hydrator;
    }
}