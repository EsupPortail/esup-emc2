<?php

namespace Formation\Form\Justificatif;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class JustificatifFormFactory {

    /**
     * @param ContainerInterface $container
     * @return JustificatifForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : JustificatifForm
    {
        /** @var JustificatifHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(JustificatifHydrator::class);

        $form = new JustificatifForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}