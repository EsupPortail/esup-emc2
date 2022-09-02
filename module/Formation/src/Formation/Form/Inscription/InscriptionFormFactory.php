<?php

namespace Formation\Form\Inscription;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class InscriptionFormFactory {

    /**
     * @param ContainerInterface $container
     * @return InscriptionForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : InscriptionForm
    {
        /** @var InscriptionHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(InscriptionHydrator::class);

        $form = new InscriptionForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}