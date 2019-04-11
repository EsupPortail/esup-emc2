<?php

namespace Autoform\Form\Categorie;

use Zend\Stdlib\Hydrator\HydratorPluginManager;

class CategorieHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /** @var CategorieHydrator $hydrator */
        $hydrator = new CategorieHydrator();
        return $hydrator;
    }
}