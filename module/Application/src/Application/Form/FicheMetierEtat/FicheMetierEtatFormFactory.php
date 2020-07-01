<?php

namespace Application\Form\FicheMetierEtat;

use Application\Service\FicheMetierEtat\FicheMetierEtatService;
use Interop\Container\ContainerInterface;

class FicheMetierEtatFormFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierEtatForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FicheMetierEtatService $ficheMetierEtatService
         * @var FicheMetierEtatHydrator $ficheMetierEtatHydrator
         */
        $ficheMetierEtatService = $container->get(FicheMetierEtatService::class);
        $ficheMetierEtatHydrator = $container->get('HydratorManager')->get(FicheMetierEtatHydrator::class);

        /** @var FicheMetierEtatForm $form */
        $form = new FicheMetierEtatForm();
        $form->setFicheMetierEtatService($ficheMetierEtatService);
        $form->setHydrator($ficheMetierEtatHydrator);
        return $form;
    }
}