<?php

namespace Formation\Form\SelectionGestionnaire;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\User\UserService;

class SelectionGestionnaireHydratorFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionGestionnaireHydrator
    {
        /**
         * @var UserService $userService
         */
        $userService = $container->get(UserService::class);

        $hydrator = new SelectionGestionnaireHydrator();
        $hydrator->setUserService($userService);
        return $hydrator;
    }
}