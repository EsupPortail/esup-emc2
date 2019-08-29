<?php

namespace Indicateur\Controller\Indicateur;

use Indicateur\Form\Indicateur\IndicateurForm;
use Indicateur\Service\Abonnement\AbonnementService;
use Indicateur\Service\Indicateur\IndicateurService;
use Utilisateur\Service\User\UserService;
use Zend\Mvc\Controller\ControllerManager;

class IndicateurControllerFactory {

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

        /**
         * @var IndicateurForm $indicateurForm
         */
        $indicateurForm = $manager->getServiceLocator()->get('FormElementManager')->get(IndicateurForm::class);

        /** @var IndicateurController $controller */
        $controller = new IndicateurController();
        $controller->setAbonnementService($abonnementService);
        $controller->setIndicateurService($indicateurService);
        $controller->setUserService($userService);
        $controller->setIndicateurForm($indicateurForm);
        return $controller;
    }
}