<?php

namespace Application\Form\RessourceRh;

use Interop\Container\ContainerInterface;

class FamilleProfessionnelleFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FamilleProfessionnelleHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FamilleProfessionnelleHydrator::class);

        $form = new FamilleProfessionnelleForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}