<?php

namespace Indicateur\Controller;

use Indicateur\Service\Abonnement\AbonnementService;
use Indicateur\Service\Indicateur\IndicateurService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class IndexControllerFactory {

    public function __invoke(ContainerInterface $container) : IndexController
    {
        /**
         * @var AbonnementService $abonnementService
         * @var IndicateurService $indicateurService
         * @var UserService $userService
         */
        $abonnementService = $container->get(AbonnementService::class);
        $indicateurService = $container->get(IndicateurService::class);
        $userService = $container->get(UserService::class);

        $controller  = new IndexController();
        $controller->setAbonnementService($abonnementService);
        $controller->setIndicateurService($indicateurService);
        $controller->setUserService($userService);
        return $controller;
    }
}