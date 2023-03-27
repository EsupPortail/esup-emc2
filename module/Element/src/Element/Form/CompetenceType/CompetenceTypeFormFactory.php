<?php

namespace Element\Form\CompetenceType;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceTypeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceTypeForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceTypeForm
    {
        /** @var CompetenceTypeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceTypeHydrator::class);

        /** @var CompetenceTypeForm $form */
        $form = new CompetenceTypeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}