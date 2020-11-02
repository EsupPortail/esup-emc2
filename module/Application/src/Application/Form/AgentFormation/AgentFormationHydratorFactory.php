<?php

namespace Application\Form\AgentFormation;

use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class AgentFormationHydratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return AgentFormationHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         */
        $formationService = $container->get(FormationService::class);

        /** @var AgentFormationHydrator $hydrator */
        $hydrator = new AgentFormationHydrator();
        $hydrator->setFormationService($formationService);
        return $hydrator;
    }
}