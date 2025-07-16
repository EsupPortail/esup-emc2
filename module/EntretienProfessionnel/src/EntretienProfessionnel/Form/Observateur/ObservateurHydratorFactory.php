<?php

namespace EntretienProfessionnel\Form\Observateur;

use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
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
        /**
         * @var EntretienProfessionnelService $entretienProfesionnelService
         * @var UserService $userService
         */
        $entretienProfesionnelService = $container->get(EntretienProfessionnelService::class);
        $userService = $container->get(UserService::class);

        $hydrator = new ObservateurHydrator();
        $hydrator->setEntretienProfessionnelService($entretienProfesionnelService);
        $hydrator->setUserService($userService);
        return $hydrator;
    }
}