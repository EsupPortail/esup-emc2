<?php

namespace Metier\Form\Referentiel;

use Interop\Container\ContainerInterface;

class ReferentielFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var ReferentielHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ReferentielHydrator::class);

        /** @var ReferentielForm $form */
        $form = new ReferentielForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}