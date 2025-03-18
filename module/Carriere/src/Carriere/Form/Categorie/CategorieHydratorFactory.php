<?php

namespace Carriere\Form\Categorie;

use Interop\Container\ContainerInterface;

class CategorieHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return CategorieHydrator
     */
    public function __invoke(ContainerInterface $container) : CategorieHydrator
    {
        $hydrator = new CategorieHydrator();
        return $hydrator;
    }
}