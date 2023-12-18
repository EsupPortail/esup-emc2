<?php

namespace Formation\Form\Justification;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class JustificationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return JustificationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : JustificationForm
    {
        /** @var JustificationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(JustificationHydrator::class);

        $form = new JustificationForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}