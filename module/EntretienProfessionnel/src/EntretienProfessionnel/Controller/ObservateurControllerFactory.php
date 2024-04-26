<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\Observateur\ObservateurForm;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Observateur\ObservateurService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\User\UserService;

class ObservateurControllerFactory
{

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservateurController
    {
        /**
         * @var CampagneService $campagneService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var ObservateurService $observateurService
         * @var UserService $userService
         * @var ObservateurForm $observateurForm
         */
        $campagneService = $container->get(CampagneService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $observateurService = $container->get(ObservateurService::class);
        $userService = $container->get(UserService::class);
        $observateurForm = $container->get('FormElementManager')->get(ObservateurForm::class);

        $controller = new ObservateurController();
        $controller->setCampagneService($campagneService);
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setObservateurService($observateurService);
        $controller->setUserService($userService);
        $controller->setObservateurForm($observateurForm);
        return $controller;
    }
}