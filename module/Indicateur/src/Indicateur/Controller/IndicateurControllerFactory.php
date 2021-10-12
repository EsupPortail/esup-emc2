<?php

namespace Indicateur\Controller;

use Indicateur\Form\Indicateur\IndicateurForm;
use Indicateur\Service\Abonnement\AbonnementService;
use Indicateur\Service\Indicateur\IndicateurService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class IndicateurControllerFactory {

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

        /**
         * @var IndicateurForm $indicateurForm
         */
        $indicateurForm = $container->get('FormElementManager')->get(IndicateurForm::class);

        /** @var IndicateurController $controller */
        $controller = new IndicateurController();
        $controller->setAbonnementService($abonnementService);
        $controller->setIndicateurService($indicateurService);
        $controller->setUserService($userService);
        $controller->setIndicateurForm($indicateurForm);
        return $controller;
    }
}