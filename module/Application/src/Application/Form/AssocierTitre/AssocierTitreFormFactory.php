<?php

namespace Application\Form\AssocierTitre;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AssocierTitreFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AssocierTitreForm
    {
        /** @var AssocierTitreHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AssocierTitreHydrator::class);

        $form = new AssocierTitreForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}