<?php

namespace Application\Form\RessourceRh;

use Application\Service\Domaine\DomaineService;
use Interop\Container\ContainerInterface;

class MetierHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var DomaineService $domaineService */
        $domaineService = $container->get(DomaineService::class);

        $hydrator = new MetierHydrator();
        $hydrator->setDomaineService($domaineService);

        return $hydrator;
    }
}