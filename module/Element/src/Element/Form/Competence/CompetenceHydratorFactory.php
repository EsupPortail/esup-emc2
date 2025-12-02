<?php

namespace Element\Form\Competence;

use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceDiscipline\CompetenceDisciplineService;
use Element\Service\CompetenceTheme\CompetenceThemeService;
use Element\Service\CompetenceType\CompetenceTypeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\Referentiel\ReferentielService;

class CompetenceHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return CompetenceHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CompetenceHydrator
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceDisciplineService $competenceDisciplineService
         * @var CompetenceThemeService $competenceThemeService
         * @var CompetenceTypeService $competenceTypeService
         * @var ReferentielService $referentielService
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceDisciplineService = $container->get(CompetenceDisciplineService::class);
        $referentielService = $container->get(ReferentielService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);

        $hydrator = new CompetenceHydrator();
        $hydrator->setCompetenceService($competenceService);
        $hydrator->setCompetenceDisciplineService($competenceDisciplineService);
        $hydrator->setReferentielService($referentielService);
        $hydrator->setCompetenceThemeService($competenceThemeService);
        $hydrator->setCompetenceTypeService($competenceTypeService);
        return $hydrator;
    }
}