<?php

namespace Formation\Form\StagiaireExterne;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class StagiaireExterneFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): StagiaireExterneForm
    {
        $hydrator = $container->get('HydratorManager')->get(StagiaireExterneHydrator::class);

        $form = new StagiaireExterneForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}