<?php

namespace Application\Form\RessourceRh;

use Interop\Container\ContainerInterface;

class MissionSpecifiqueThemeFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var MissionSpecifiqueThemeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MissionSpecifiqueThemeHydrator::class);

        /** @var MissionSpecifiqueThemeForm $form */
        $form = new MissionSpecifiqueThemeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}