<?php

namespace Application\Form\ModifierLibelle;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ModifierLibelleFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ModifierLibelleForm
    {
        /**
         * @var ModifierLibelleHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(ModifierLibelleHydrator::class);

        $form = new ModifierLibelleForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}