<?php

namespace Formation\Provider;

use Formation\Service\Formateur\FormateurService;
use Formation\Service\StagiaireExterne\StagiaireExterneService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class IdentityProviderFactory
{
    /**
     * @param ContainerInterface $container
     * @return IdentityProvider
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): IdentityProvider
    {
        /**
         * @var FormateurService $formateurService
         * @var StagiaireExterneService $stagiaireExterneService
         */
        $formateurService = $container->get(FormateurService::class);
        $stagiaireExterneService = $container->get(StagiaireExterneService::class);
        $roleService = $container->get(RoleService::class);
        $userService = $container->get(UserService::class);

        $service = new IdentityProvider();
        $service->setFormateurService($formateurService);
        $service->setStagiaireExterneService($stagiaireExterneService);
        $service->setRoleService($roleService);
        $service->setUserService($userService);
        return $service;
    }
}