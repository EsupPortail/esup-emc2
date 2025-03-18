<?php

namespace Application\Form\SpecificitePoste;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SpecificitePosteFormFactory{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SpecificitePosteForm
    {
        /** @var SpecificitePosteHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(SpecificitePosteHydrator::class);

        $form = new SpecificitePosteForm();
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}