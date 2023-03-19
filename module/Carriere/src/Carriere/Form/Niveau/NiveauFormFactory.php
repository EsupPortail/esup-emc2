<?php

namespace Carriere\Form\Niveau;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NiveauFormFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : NiveauForm
    {
        /**
         * @var NiveauHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(NiveauHydrator::class);

        $form = new NiveauForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}