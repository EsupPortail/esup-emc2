<?php

namespace Application\Form\SpecificitePoste;

use Interop\Container\ContainerInterface;

class SpecificitePosteFormFactory{

    public function __invoke(ContainerInterface $container)
    {
        /** @var SpecificitePosteHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(SpecificitePosteHydrator::class);

        /** @var SpecificitePosteForm $form */
        $form = new SpecificitePosteForm();
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}