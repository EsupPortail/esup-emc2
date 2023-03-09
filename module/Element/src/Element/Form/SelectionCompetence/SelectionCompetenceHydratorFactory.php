<?php

namespace Element\Form\SelectionCompetence;

use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionCompetenceHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionCompetenceHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SelectionCompetenceHydrator
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceElementService $competenceElementService
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);

        $hydrator = new SelectionCompetenceHydrator();
        $hydrator->setCompetenceService($competenceService);
        $hydrator->setCompetenceElementService($competenceElementService);
        return $hydrator;
    }
}