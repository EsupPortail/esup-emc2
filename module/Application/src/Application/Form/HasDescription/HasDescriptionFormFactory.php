<?php

namespace Application\Form\HasDescription;

use Interop\Container\ContainerInterface;

class HasDescriptionFormFactory {

    /**
     * @param ContainerInterface $container
     * @return HasDescriptionForm
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