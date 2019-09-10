<?php

namespace Application\Form\Formation;

use Interop\Container\ContainerInterface;

class FormationFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(FormationHydrator::class);

        /** @var FormationForm $form */
        $form = new FormationForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}