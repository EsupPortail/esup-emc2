<?php

namespace Formation\Form\FormationElement;

use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class FormationElementHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationElementHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         */
        $formationService = $container->get(FormationService::class);

        /** @var FormationElementHydrator $hydrator */
        $hydrator = new FormationElementHydrator();
        $hydrator->setFormationService($formationService);
        return $hydrator;
    }
}