<?php

namespace UnicaenUtilisateur\Form\User;

use Interop\Container\ContainerInterface;

class UserHydratorFactory {

    public function __invoke(ContainerInterface $container) 
    {
        /** @var UserHydrator $hydrator */
        $hydrator = new UserHydrator();
        return $hydrator;
    }
}