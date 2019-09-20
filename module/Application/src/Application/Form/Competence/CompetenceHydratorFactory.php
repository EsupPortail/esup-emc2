<?php

namespace Application\Form\Competence;

use Application\Service\Competence\CompetenceService;
use Interop\Container\ContainerInterface;

class CompetenceHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var CompetenceService $competenceService */
        $competenceService = $container->get(CompetenceService::class);

        /** @var CompetenceHydrator $hydrator */
        $hydrator = new CompetenceHydrator();
        $hydrator->setCompetenceService($competenceService);
        return $hydrator;
    }
}