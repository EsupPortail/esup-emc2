<?php

namespace Application\Form\FicheMetierEtat;

use Application\Service\FicheMetierEtat\FicheMetierEtatService;
use Interop\Container\ContainerInterface;

class FicheMetierEtatHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierEtatHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FicheMetierEtatService $ficheMetierEtatService
         */
        $ficheMetierEtatService = $container->get(FicheMetierEtatService::class);

        /** @var FicheMetierEtatHydrator $hydrator */
        $hydrator = new FicheMetierEtatHydrator();
        $hydrator->setFicheMetierEtatService($ficheMetierEtatService);
        return $hydrator;
    }
}