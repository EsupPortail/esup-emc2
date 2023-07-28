<?php

namespace Formation\Form\FormationGroupe;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationGroupeFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationGroupeForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationGroupeForm
    {
        /** @var FormationGroupeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationGroupeHydrator::class);

        /** @var FormationGroupeForm $form */
        $form = new FormationGroupeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}