<?php

namespace Metier\Form\MetierNiveau;

use Application\Service\Niveau\NiveauService;
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
         * @var NiveauService $metierService
         */
        $metierService = $container->get(MetierService::class);
        $niveauService = $container->get(NiveauService::class);

        $hydrator = new MetierNiveauHydrator();
        $hydrator->setMetierService($metierService);
        $hydrator->setNiveauService($niveauService);
        return $hydrator;
    }
}