<?php

namespace UnicaenPrivilege\Form\CategoriePrivilege;

use Interop\Container\ContainerInterface;

class CategoriePrivilegeHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var CategoriePrivilegeHydrator $hydrator */
        $hydrator = new CategoriePrivilegeHydrator();
        return $hydrator;
    }
}