<?php

namespace Metier\Form\SelectionnerMetier;

use Metier\Service\Metier\MetierService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerMetierHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionnerMetierHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SelectionnerMetierHydrator
    {
        /** @var MetierService $metierService */
        $metierService = $container->get(MetierService::class);

        $hydrator = new SelectionnerMetierHydrator();
        $hydrator->setMetierService($metierService);

        return $hydrator;
    }
}