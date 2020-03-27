<?php

namespace Application\Form\ValidationDemande;

use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class ValidationDemandeHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var UserService $userService
         */
        $userService = $container->get(UserService::class);

        /** @var ValidationDemandeHydrator $hydrator */
        $hydrator = new ValidationDemandeHydrator();
        $hydrator->setUserService($userService);
        return $hydrator;
    }
}