<?php

namespace EntretienProfessionnel\Form\Recours;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class RecoursFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RecoursForm
    {
        /** @var RecoursHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(RecoursHydrator::class);

        $form = new RecoursForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}