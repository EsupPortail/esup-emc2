<?php

namespace Formation\Form\Session;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SessionFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SessionForm
    {
        /** @var SessionHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(SessionHydrator::class);

        $form = new SessionForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}