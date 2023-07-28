<?php

namespace Formation\Form\FormationInstanceFrais;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationInstanceFraisFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceFraisForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationInstanceFraisForm
    {
        /** @var FormationInstanceFraisHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationInstanceFraisHydrator::class);

        $form = new FormationInstanceFraisForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}