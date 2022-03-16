<?php

namespace Element\Form\CompetenceElement;

use Element\Service\Competence\CompetenceService;
use Element\Service\Niveau\NiveauService;
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