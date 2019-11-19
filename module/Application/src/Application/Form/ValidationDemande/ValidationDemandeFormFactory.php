<?php

namespace Application\Form\ValidationDemande;

use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class ValidationDemandeFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var UserService $userService
         * @var ValidationDemandeHydrator $hydrator
         */
        $userService = $container->get(UserService::class);
        $hydrator = $container->get('HydratorManager')->get(ValidationDemandeHydrator::class);

        /** @var ValidationDemandeForm $form */
        $form = new ValidationDemandeForm();
        $form->setUserService($userService);
        $form->setHydrator($hydrator);
        return $form;
    }
}