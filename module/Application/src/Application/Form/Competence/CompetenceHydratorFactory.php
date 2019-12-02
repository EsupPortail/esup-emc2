<?php

namespace Application\Form\Competence;

use Application\Service\Competence\CompetenceService;
use Application\Service\CompetenceTheme\CompetenceThemeService;
use Application\Service\CompetenceType\CompetenceTypeService;
use Interop\Container\ContainerInterface;

class CompetenceHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceThemeService $competenceThemeService
         * @var CompetenceTypeService $competenceTypeService
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);

        /** @var CompetenceHydrator $hydrator */
        $hydrator = new CompetenceHydrator();
        $hydrator->setCompetenceService($competenceService);
        $hydrator->setCompetenceThemeService($competenceThemeService);
        $hydrator->setCompetenceTypeService($competenceTypeService);
        return $hydrator;
    }
}