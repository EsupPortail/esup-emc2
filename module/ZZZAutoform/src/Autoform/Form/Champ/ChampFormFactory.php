<?php

namespace Autoform\Form\Champ;

use Interop\Container\ContainerInterface;

class ChampFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var ChampHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ChampHydrator::class);

        /** @var  ChampForm $form */
        $form = new ChampForm();
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}