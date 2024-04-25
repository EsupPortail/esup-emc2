<?php

namespace EntretienProfessionnel\Form\Observateur;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ObservateurFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservateurForm
    {
        /** @var ObservateurHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ObservateurHydrator::class);

        $form = new ObservateurForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}