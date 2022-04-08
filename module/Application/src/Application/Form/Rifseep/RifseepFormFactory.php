<?php

namespace Application\Form\Rifseep;

use Interop\Container\ContainerInterface;

class RifseepFormFactory{

    public function __invoke(ContainerInterface $container) : RifseepForm
    {
        /** @var RifseepHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(RifseepHydrator::class);

        $form = new RifseepForm();
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}