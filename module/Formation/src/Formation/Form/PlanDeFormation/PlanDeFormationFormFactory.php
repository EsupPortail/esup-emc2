<?php

namespace Formation\Form\PlanDeFormation;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PlanDeFormationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return PlanDeFormationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : PlanDeFormationForm
    {
        /** @var PlanDeFormationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(PlanDeFormationHydrator::class);

        $form = new PlanDeFormationForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}