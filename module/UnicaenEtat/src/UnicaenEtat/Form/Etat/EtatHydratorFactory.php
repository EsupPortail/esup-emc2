<?php

namespace UnicaenEtat\Form\Etat;

use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class EtatHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return EtatHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EtatTypeService $etatTypeService
         */
        $etatTypeService = $container->get(EtatTypeService::class);

        $hydrator = new EtatHydrator();
        $hydrator->setEtatTypeService($etatTypeService);
        return $hydrator;
    }
}