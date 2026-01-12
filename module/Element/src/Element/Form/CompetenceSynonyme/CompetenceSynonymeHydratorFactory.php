<?php

namespace Element\Form\CompetenceSynonyme;

use Element\Service\Competence\CompetenceService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceSynonymeHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CompetenceSynonymeHydrator
    {
        /** @var CompetenceService $competenceService */
        $competenceService = $container->get(CompetenceService::class);

        $hydrator = new CompetenceSynonymeHydrator();
        $hydrator->setCompetenceService($competenceService);
        return $hydrator;
    }

}
