<?php

namespace CompetenceSynonyme;

use Element\Form\CompetenceSynonyme\CompetenceSynonymeHydrator;
use Element\Service\Competence\CompetenceService;
use Psr\Container\ContainerInterface;

class CompetenceSynonymeHydratorFactory
{

    public function __invoke(ContainerInterface $container): CompetenceSynonymeHydrator
    {
        /** @var CompetenceService $competenceService */
        $competenceService = $container->get(CompetenceService::class);

        $hydrator = new CompetenceSynonymeHydrator();
        $hydrator->setCompetenceService($competenceService);
        return $hydrator;
    }

}
