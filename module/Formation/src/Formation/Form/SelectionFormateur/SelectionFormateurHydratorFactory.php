<?php

namespace Formation\Form\SelectionFormateur;

use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionFormateurHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return SelectionFormateurHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionFormateurHydrator
    {
        /**
         * @var FormationService $formationService
         */
        $formationService = $container->get(FormationService::class);

        $hydrator = new SelectionFormateurHydrator();
        $hydrator->setFormationService($formationService);
        return $hydrator;
    }
}