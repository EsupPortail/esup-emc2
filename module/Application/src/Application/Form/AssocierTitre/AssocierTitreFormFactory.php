<?php

namespace Application\Form\AssocierTitre;

use Interop\Container\ContainerInterface;

class AssocierTitreFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var AssocierTitreHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AssocierTitreHydrator::class);

        /** @var AssocierTitreForm $form */
        $form = new AssocierTitreForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}