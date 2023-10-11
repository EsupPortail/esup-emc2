<?php

namespace Formation\Form\FormationElement;

use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationElementHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationElementHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationElementHydrator
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