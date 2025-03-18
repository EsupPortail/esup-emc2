<?php

namespace Application\Form\HasPeriode;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class HasPeriodeFormFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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