<?php

namespace FichePoste\Form\Expertise;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ExpertiseFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ExpertiseForm
    {
        $hydrator = $container->get('HydratorManager')->get(ExpertiseHydrator::class);

        $form = new ExpertiseForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}
