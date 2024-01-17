<?php

namespace Formation\Form\SelectionFormation;

use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionFormationHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return SelectionFormationHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionFormationHydrator
    {
        /**
         * @var FormationService $formationService
         */
        $formationService = $container->get(FormationService::class);

        $hydrator = new SelectionFormationHydrator();
        $hydrator->setFormationService($formationService);
        return $hydrator;
    }
}