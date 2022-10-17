<?php

namespace Formation\Form\Demande2Formation;

use Psr\Container\ContainerInterface;

class Demande2FormationFormFactory {

    public function __invoke(ContainerInterface $container) : Demande2FormationForm
    {
        /** @var Demande2FormationHydrator $hydrator  */
        $hydrator = $container->get('HydratorManager')->get(Demande2FormationHydrator::class);

        $form = new Demande2FormationForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}