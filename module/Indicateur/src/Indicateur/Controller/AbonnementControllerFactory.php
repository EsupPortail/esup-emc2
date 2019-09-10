<?php

namespace Indicateur\Controller;

use Indicateur\Service\Abonnement\AbonnementService;
use Indicateur\Service\Indicateur\IndicateurService;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class AbonnementControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AbonnementService $abonnementService
         * @var IndicateurService $indicateurService
         * @var UserService $userService
         */
        $abonnementService = $container->get(AbonnementService::class);
        $indicateurService = $container->get(IndicateurService::class);
        $userService = $container->get(UserService::class);

        /** @var AbonnementController $controller */
        $controller = new AbonnementController();
        $controller->setAbonnementService($abonnementService);
        $controller->setIndicateurService($indicateurService);
        $controller->setUserService($userService);
        return $controller;
    }
}