<?php

namespace Application\Form\ModifierDescription;

use Interop\Container\ContainerInterface;

class ModifierDescriptionFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ModifierDescriptionForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ModifierDescriptionHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(ModifierDescriptionHydrator::class);

        /** @var ModifierDescriptionForm $form */
        $form = new ModifierDescriptionForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}