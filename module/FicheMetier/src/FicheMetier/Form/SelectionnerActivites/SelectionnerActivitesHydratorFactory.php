<?php

namespace FicheMetier\Form\SelectionnerActivites;

use FicheMetier\Service\Activite\ActiviteService;
use FicheMetier\Service\ActiviteElement\ActiviteElementService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerActivitesHydratorFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionnerActivitesHydrator
    {
        /**
         * @var ActiviteService $activiteService
         * @var ActiviteElementService $activiteElementService
         */
        $activiteService = $container->get(ActiviteService::class);
        $activiteElementService = $container->get(ActiviteElementService::class);

        $hydrator = new SelectionnerActivitesHydrator();
        $hydrator->setActiviteService($activiteService);
        $hydrator->setActiviteElementService($activiteElementService);
        return $hydrator;
    }
}
