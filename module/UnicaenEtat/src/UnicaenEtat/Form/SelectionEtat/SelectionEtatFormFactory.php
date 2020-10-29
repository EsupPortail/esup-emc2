<?php

namespace UnicaenEtat\Form\SelectionEtat;

use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class SelectionEtatFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionEtatForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EtatService $etatService
         * @var EtatTypeService $etatTypeService
         */
        $etatService = $container->get(EtatService::class);
        $etatTypeService = $container->get(EtatTypeService::class);

        /**
         * @var SelectionEtatHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(SelectionEtatHydrator::class);

        $form = new SelectionEtatForm();
        $form->setEtatService($etatService);
        $form->setEtatTypeService($etatTypeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}