<?php

namespace FicheMetier\Form\CodeFonction;

use Psr\Container\ContainerInterface;

class CodeFonctionHydratorFactory {

    public function __invoke(ContainerInterface $container): CodeFonctionHydrator
    {
        $hydrator = new CodeFonctionHydrator();
        return $hydrator;
    }
}