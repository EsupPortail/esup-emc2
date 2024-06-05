<?php

namespace Formation\Form\FormationInstance;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationInstanceFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationInstanceForm
    {
        /** @var FormationInstanceHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationInstanceHydrator::class);

        $form = new FormationInstanceForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}