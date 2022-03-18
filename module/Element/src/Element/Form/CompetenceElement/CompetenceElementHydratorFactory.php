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
         * @var NiveauService $maitriseNiveauService
         */
        $competenceService = $container->get(CompetenceService::class);
        $maitriseNiveauService = $container->get(NiveauService::class);

        /** @var CompetenceElementHydrator $hydrator */
        $hydrator = new CompetenceElementHydrator();
        $hydrator->setCompetenceService($competenceService);
        $hydrator->setNiveauService($maitriseNiveauService);
        return $hydrator;
    }
}