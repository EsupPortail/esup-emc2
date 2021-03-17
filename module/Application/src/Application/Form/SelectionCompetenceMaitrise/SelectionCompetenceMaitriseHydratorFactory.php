<?php

namespace Application\Form\SelectionCompetenceMaitrise;

use Application\Service\CompetenceMaitrise\CompetenceMaitriseService;
use Interop\Container\ContainerInterface;

class SelectionCompetenceMaitriseHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionCompetenceMaitriseHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CompetenceMaitriseService $competenceMaitriseService
         */
        $competenceMaitriseService = $container->get(CompetenceMaitriseService::class);

        $hydrator = new SelectionCompetenceMaitriseHydrator();
        $hydrator->setCompetenceMaitriseService($competenceMaitriseService);
        return $hydrator;
    }
}