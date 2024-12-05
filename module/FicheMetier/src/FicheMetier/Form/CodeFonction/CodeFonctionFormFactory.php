<?php

namespace FicheMetier\Form\CodeFonction;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CodeFonctionFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CodeFonctionForm
    {
        /** @var CodeFonctionHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CodeFonctionHydrator::class);

        $form = new CodeFonctionForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}