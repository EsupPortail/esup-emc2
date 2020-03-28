<?php

namespace Application\Form\ModifierLibelle;

use Interop\Container\ContainerInterface;

class ModifierLibelleFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ModifierLibelleForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ModifierLibelleHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(ModifierLibelleHydrator::class);

        /** @var ModifierLibelleForm $form */
        $form = new ModifierLibelleForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}