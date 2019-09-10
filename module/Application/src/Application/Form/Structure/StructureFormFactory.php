<?php

namespace Application\Form\Structure;

use Interop\Container\ContainerInterface;

class StructureFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var StructureHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(StructureHydrator::class);

        /** @var StructureForm $form */
        $form = new StructureForm();
        $form->setHydrator($hydrator);
        $form->init();
        return $form;
    }

}