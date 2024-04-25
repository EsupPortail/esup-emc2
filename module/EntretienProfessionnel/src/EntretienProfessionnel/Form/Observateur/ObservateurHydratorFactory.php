<?php

namespace EntretienProfessionnel\Form\Observateur;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\User\UserService;

class ObservateurHydratorFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservateurHydrator
    {
        /** @var UserService $userService */
        $userService = $container->get(UserService::class);

        $hydrator = new ObservateurHydrator();
        $hydrator->setUserService($userService);
        return $hydrator;
    }
}