<?php

namespace Application\Form\Rifseep;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class RifseepFormFactory{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
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