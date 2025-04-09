<?php

namespace Metier\Form\Domaine;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DomaineFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return DomaineForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DomaineForm
    {

        /** @var DomaineHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(DomaineHydrator::class);

        $form = new DomaineForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}