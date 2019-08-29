<?php

namespace Indicateur\Controller\Abonnement;

use Indicateur\Service\Abonnement\AbonnementService;
use Indicateur\Service\Indicateur\IndicateurService;
use Utilisateur\Service\User\UserService;
use Zend\Mvc\Controller\ControllerManager;

class AbonnementControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var AbonnementService $abonnementService
         * @var IndicateurService $indicateurService
         * @var UserService $userService
         */
        $abonnementService = $manager->getServiceLocator()->get(AbonnementService::class);
        $indicateurService = $manager->getServiceLocator()->get(IndicateurService::class);
        $userService = $manager->getServiceLocator()->get(UserService::class);

        /** @var AbonnementController $controller */
        $controller = new AbonnementController();
        $controller->setAbonnementService($abonnementService);
        $controller->setIndicateurService($indicateurService);
        $controller->setUserService($userService);
        return $controller;
    }
}