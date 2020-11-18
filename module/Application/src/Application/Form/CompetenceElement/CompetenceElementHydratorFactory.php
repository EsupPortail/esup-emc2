<?php

namespace Application\Form\CompetenceElement;

use Application\Service\Competence\CompetenceService;
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
         */
        $competenceService = $container->get(CompetenceService::class);

        /** @var CompetenceElementHydrator $hydrator */
        $hydrator = new CompetenceElementHydrator();
        $hydrator->setCompetenceService($competenceService);
        return $hydrator;
    }
}