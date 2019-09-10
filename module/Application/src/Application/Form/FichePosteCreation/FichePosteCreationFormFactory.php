<?php

namespace Application\Form\FichePosteCreation;

use Interop\Container\ContainerInterface;

class FichePosteCreationFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FichePosteCreationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FichePosteCreationHydrator::class);

        /** @var FichePosteCreationForm $form */
        $form = new FichePosteCreationForm();
        $form->setHydrator($hydrator);
        $form->init();


        return $form;
    }

}