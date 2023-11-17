<?php

namespace Formation\Form\FormationGroupe;

use Formation\Service\Axe\AxeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationGroupeHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationGroupeHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationGroupeHydrator
    {
        /**
         * @var AxeService $axeService
         */
        $axeService = $container->get(AxeService::class);

        $hydrator = new FormationGroupeHydrator();
        $hydrator->setAxeService($axeService);
        return $hydrator;
    }
}