<?php

namespace FicheMetier\Form\CodeEmploiType;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CodeEmploiTypeFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CodeEmploiTypeForm
    {
        /**
         * @var CodeEmploiTypeHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(CodeEmploiTypeHydrator::class);

        $form = new CodeEmploiTypeForm();
        $form->setHydrator($hydrator);
        return $form;

    }
}
