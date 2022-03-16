<?php

namespace Element\Form\CompetenceType;

use Interop\Container\ContainerInterface;

class CompetenceTypeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceTypeForm
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