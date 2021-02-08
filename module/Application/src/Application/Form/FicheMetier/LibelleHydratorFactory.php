<?php

namespace Application\Form\FicheMetier;

use Metier\Service\Metier\MetierService;
use Interop\Container\ContainerInterface;

class LibelleHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var MetierService $metierService */
        $metierService = $container->get(MetierService::class);

        $hydrator = new LibelleHydrator();
        $hydrator->setMetierService($metierService);

        return $hydrator;
    }
}