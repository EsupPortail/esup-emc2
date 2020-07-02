<?php

namespace Application\Form\SelectionFicheMetierEtat;

use Application\Service\FicheMetierEtat\FicheMetierEtatService;
use Interop\Container\ContainerInterface;

class SelectionFicheMetierEtatFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionFicheMetierEtatForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FicheMetierEtatService $ficheMetierEtatService
         * @var SelectionFicheMetierEtatHydrator $ficheMetierEtatHydrator
         */
        $ficheMetierEtatService = $container->get(FicheMetierEtatService::class);
        $ficheMetierEtatHydrator = $container->get('HydratorManager')->get(SelectionFicheMetierEtatHydrator::class);

        /** @var SelectionFicheMetierEtatForm $form */
        $form = new SelectionFicheMetierEtatForm();
        $form->setFicheMetierEtatService($ficheMetierEtatService);
        $form->setHydrator($ficheMetierEtatHydrator);
        return $form;
    }
}