<?php

namespace Formation\Form\FormationJournee;

use Interop\Container\ContainerInterface;

class FormationJourneeFormFactory
{

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationJourneeHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(FormationJourneeHydrator::class);

        /** @var FormationJourneeForm $form */
        $form = new FormationJourneeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}