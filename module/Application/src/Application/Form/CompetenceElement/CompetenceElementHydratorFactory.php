<?php

namespace Application\Form\CompetenceElement;

use Application\Service\Competence\CompetenceService;
use Application\Service\CompetenceMaitrise\CompetenceMaitriseService;
use Application\Service\MaitriseNiveau\MaitriseNiveauService;
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
         * @var MaitriseNiveauService $maitriseNiveauService
         */
        $competenceService = $container->get(CompetenceService::class);
        $maitriseNiveauService = $container->get(MaitriseNiveauService::class);

        /** @var CompetenceElementHydrator $hydrator */
        $hydrator = new CompetenceElementHydrator();
        $hydrator->setCompetenceService($competenceService);
        $hydrator->setMaitriseNiveauService($maitriseNiveauService);
        return $hydrator;
    }
}