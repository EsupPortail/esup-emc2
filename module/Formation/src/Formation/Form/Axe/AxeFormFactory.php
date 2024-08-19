<?php

namespace Formation\Form\Axe;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AxeFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return AxeForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AxeForm
    {
        /** @var AxeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AxeHydrator::class);

        $form = new AxeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}