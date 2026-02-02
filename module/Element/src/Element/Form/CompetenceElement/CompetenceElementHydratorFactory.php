<?php

namespace Element\Form\CompetenceElement;

use Element\Service\Competence\CompetenceService;
use Element\Service\NiveauMaitrise\NiveauMaitriseService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceElementHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceElementHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceElementHydrator
    {
        /**
         * @var CompetenceService $competenceService
         * @var NiveauMaitriseService $maitriseNiveauService
         */
        $competenceService = $container->get(CompetenceService::class);
        $maitriseNiveauService = $container->get(NiveauMaitriseService::class);

        $hydrator = new CompetenceElementHydrator();
        $hydrator->setCompetenceService($competenceService);
        $hydrator->setNiveauMaitriseService($maitriseNiveauService);
        return $hydrator;
    }
}