<?php

namespace Application\Form\Categorie;

use Interop\Container\ContainerInterface;

class CategorieHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return CategorieHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var CategorieHydrator $hydrator */
        $hydrator = new CategorieHydrator();
        return $hydrator;
    }
}