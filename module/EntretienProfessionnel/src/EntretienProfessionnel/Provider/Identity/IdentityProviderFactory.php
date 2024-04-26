<?php

namespace EntretienProfessionnel\Provider\Identity;

use EntretienProfessionnel\Service\Observateur\ObservateurService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class IdentityProviderFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): IdentityProvider
    {
        /**
         * @var ObservateurService $observateurService
         * @var RoleService $roleService
         * @var UserService $userService
         */
        $observateurService = $container->get(ObservateurService::class);
        $roleService = $container->get(RoleService::class);
        $userService = $container->get(UserService::class);

        $service = new IdentityProvider();
        $service->setObservateurService($observateurService);
        $service->setRoleService($roleService);
        $service->setUserService($userService);
        return $service;
    }
}