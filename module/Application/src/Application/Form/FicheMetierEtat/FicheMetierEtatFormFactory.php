<?php

namespace Application\Form\FicheMetierEtat;

use Interop\Container\ContainerInterface;

class FicheMetierEtatFormFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierEtatForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FicheMetierEtatHydrator $ficheMetierEtatHydrator
         */
        $ficheMetierEtatHydrator = $container->get('HydratorManager')->get(FicheMetierEtatHydrator::class);

        /** @var FicheMetierEtatForm $form */
        $form = new FicheMetierEtatForm();
        $form->setHydrator($ficheMetierEtatHydrator);
        return $form;
    }
}