<?php

namespace Formation\Form\DemandeExterne;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DemandeExterneFormFactory {

    /**
     * @param ContainerInterface $container
     * @return DemandeExterneForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : DemandeExterneForm
    {
        /** @var DemandeExterneHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(DemandeExterneHydrator::class);

        $form = new DemandeExterneForm();
        $form->setHydrator($hydrator);
        return $form;
    }


}