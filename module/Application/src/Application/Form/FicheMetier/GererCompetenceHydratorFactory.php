<?php

namespace Application\Form\FicheMetier;

use Application\Service\Competence\CompetenceService;
use Interop\Container\ContainerInterface;

class GererCompetenceHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return GererCompetenceHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var CompetenceService $competenceService */
        $competenceService = $container->get(CompetenceService::class);

        /** @var GererCompetenceHydrator $hydrator */
        $hydrator = new GererCompetenceHydrator();
        $hydrator->setCompetenceService($competenceService);
        return $hydrator;
    }
}