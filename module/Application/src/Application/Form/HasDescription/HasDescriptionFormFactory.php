<?php

namespace Application\Form\HasDescription;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class HasDescriptionFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : HasDescriptionForm
    {
        /** @var HasDescriptionHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(HasDescriptionHydrator::class);

        $form = new HasDescriptionForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}