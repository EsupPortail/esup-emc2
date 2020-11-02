<?php

namespace Application\Form\ParcoursDeFormation;

use Formration\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class ParcoursDeFormationHydratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return ParcoursDeFormationHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         */
        $formationService = $container->get(FormationService::class);

        /** @var ParcoursDeFormationHydrator $hydrator */
        $hydrator = new ParcoursDeFormationHydrator();
        $hydrator->setFormationService($formationService);
        return $hydrator;
    }
}