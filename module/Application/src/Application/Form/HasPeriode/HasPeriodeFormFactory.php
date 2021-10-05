<?php

namespace Application\Form\HasPeriode;

use Interop\Container\ContainerInterface;

class HasPeriodeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return HasPeriodeForm
     */
    public function __invoke(ContainerInterface $container) : HasPeriodeForm
    {
        /** @var HasPeriodeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(HasPeriodeHydrator::class);

        $form = new HasPeriodeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}