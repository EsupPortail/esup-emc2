<?php

namespace Application\Form\SelectionFicheMetierEtat;

use Application\Service\FicheMetierEtat\FicheMetierEtatService;
use Interop\Container\ContainerInterface;

class SelectionFicheMetierEtatHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionFicheMetierEtatHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FicheMetierEtatService $ficheMetierEtatService
         */
        $ficheMetierEtatService = $container->get(FicheMetierEtatService::class);

        /** @var SelectionFicheMetierEtatHydrator $hydrator */
        $hydrator = new SelectionFicheMetierEtatHydrator();
        $hydrator->setFicheMetierEtatService($ficheMetierEtatService);
        return $hydrator;
    }
}