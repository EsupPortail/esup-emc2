<?php

namespace Application\Form\RessourceRh;

use Interop\Container\ContainerInterface;

class MissionSpecifiqueTypeFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var MissionSpecifiqueTypeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MissionSpecifiqueTypeHydrator::class);

        /** @var MissionSpecifiqueTypeForm $form */
        $form = new MissionSpecifiqueTypeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}