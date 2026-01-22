<?php

namespace FicheMetier\Form\CodeEmploiType;

use Psr\Container\ContainerInterface;

class CodeEmploiTypeHydratorFactory {

    public function __invoke(ContainerInterface $container) : CodeEmploiTypeHydrator
    {
        $hydrator = new CodeEmploiTypeHydrator();
        return $hydrator;
    }
}