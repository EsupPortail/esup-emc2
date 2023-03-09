<?php

namespace Metier\Form\SelectionnerDomaines;

use Metier\Service\Domaine\DomaineService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerDomainesHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionnerDomainesHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SelectionnerDomainesHydrator
    {
        /**
         * @var DomaineService $domaineService
         */
        $domaineService = $container->get(DomaineService::class);

        $hydrator = new SelectionnerDomainesHydrator();
        $hydrator->setDomaineService($domaineService);
        return $hydrator;
    }
}