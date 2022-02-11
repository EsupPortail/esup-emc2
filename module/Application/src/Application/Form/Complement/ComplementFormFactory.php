<?php

namespace Application\Form\Complement;

use Interop\Container\ContainerInterface;

class ComplementFormFactory {

    public function __invoke(ContainerInterface $container) : ComplementForm
    {
        /** @var ComplementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ComplementHydrator::class);

        $form = new ComplementForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}