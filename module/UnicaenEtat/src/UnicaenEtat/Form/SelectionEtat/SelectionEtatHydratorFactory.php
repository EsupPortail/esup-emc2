<?php

namespace UnicaenEtat\Form\SelectionEtat;

use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\Etat\EtatService;

class SelectionEtatHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionEtatHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EtatService $etatService
         */
        $etatService = $container->get(EtatService::class);

        $hydrator = new SelectionEtatHydrator();
        $hydrator->setEtatService($etatService);
        return $hydrator;
    }

}