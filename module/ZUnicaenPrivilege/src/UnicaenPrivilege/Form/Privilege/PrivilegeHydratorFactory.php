<?php

namespace UnicaenPrivilege\Form\Privilege;

use Interop\Container\ContainerInterface;

class PrivilegeHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var PrivilegeHydrator $hydrator */
        $hydrator = new PrivilegeHydrator();
        return $hydrator;
    }
}