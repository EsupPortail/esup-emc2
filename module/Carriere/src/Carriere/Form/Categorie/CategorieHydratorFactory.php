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
        /** @var CategorieHydrator $hydrator */
        $hydrator = new CategorieHydrator();
        return $hydrator;
    }
}