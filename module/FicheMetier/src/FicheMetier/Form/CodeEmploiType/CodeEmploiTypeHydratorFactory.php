<?php

namespace FicheMetier\Form\CodeEmploiType;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CodeEmploiTypeHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CodeEmploiTypeHydrator
    {
        /**
         */

        $hydrator = new CodeEmploiTypeHydrator();
        return $hydrator;

    }
}

