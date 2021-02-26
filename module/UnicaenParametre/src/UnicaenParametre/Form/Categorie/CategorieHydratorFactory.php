<?php

namespace UnicaenParametre\Form\Categorie;

use Interop\Container\ContainerInterface;

class CategorieHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return CategorieHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new CategorieHydrator();
        return $hydrator;
    }
}