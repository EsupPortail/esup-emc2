<?php

namespace Application\Form\MetierReferentiel;

use Interop\Container\ContainerInterface;

class MetierReferentielFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var MetierReferentielHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MetierReferentielHydrator::class);

        /** @var MetierReferentielForm $form */
        $form = new MetierReferentielForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}