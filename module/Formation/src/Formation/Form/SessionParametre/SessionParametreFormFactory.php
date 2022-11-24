<?php

namespace Formation\Form\SessionParametre;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SessionParametreFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SessionParametreForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SessionParametreForm
    {
        /** @var SessionParametreHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(SessionParametreHydrator::class);

        $form = new SessionParametreForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}