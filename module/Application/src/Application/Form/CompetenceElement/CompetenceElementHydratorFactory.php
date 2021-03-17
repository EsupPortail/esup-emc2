<?php

namespace Application\Form\CompetenceElement;

use Application\Service\Competence\CompetenceService;
use Application\Service\CompetenceMaitrise\CompetenceMaitriseService;
use Interop\Container\ContainerInterface;

class CompetenceElementHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceElementHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceMaitriseService $competenceMaitriseService
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceMaitriseService = $container->get(CompetenceMaitriseService::class);

        /** @var CompetenceElementHydrator $hydrator */
        $hydrator = new CompetenceElementHydrator();
        $hydrator->setCompetenceService($competenceService);
        $hydrator->setCompetenceMaitriseService($competenceMaitriseService);
        return $hydrator;
    }
}