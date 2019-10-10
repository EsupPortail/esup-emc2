<?php

namespace Application\Form\AgentCompetence;

use Application\Service\Competence\CompetenceService;
use Interop\Container\ContainerInterface;

class AgentCompetenceHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CompetenceService $competenceService
         */
        $competenceService = $container->get(CompetenceService::class);

        /** @var AgentCompetenceHydrator $hydrator */
        $hydrator = new AgentCompetenceHydrator();
        $hydrator->setCompetenceService($competenceService);
        return $hydrator;
    }
}