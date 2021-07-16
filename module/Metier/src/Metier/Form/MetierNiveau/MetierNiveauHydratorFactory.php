<?php

namespace Metier\Form\MetierNiveau;

use Interop\Container\ContainerInterface;
use Metier\Service\Metier\MetierService;

class MetierNiveauHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return MetierNiveauHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MetierService $metierService
         */
        $metierService = $container->get(MetierService::class);

        $hydrator = new MetierNiveauHydrator();
        $hydrator->setMetierService($metierService);
        return $hydrator;
    }
}