<?php

namespace Autoform\Form\Categorie;

use Interop\Container\ContainerInterface;

class CategorieHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var CategorieHydrator $hydrator */
        $hydrator = new CategorieHydrator();
        return $hydrator;
    }
}