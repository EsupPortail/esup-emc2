<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Agent\AgentService;
use EntretienProfessionnel\Form\ImporterObservateur\ImporterObservateurForm;
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
         * @var AgentService $agentService
         * @var CampagneService $campagneService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var ObservateurService $observateurService
         * @var UserService $userService
         * @var ImporterObservateurForm $importerObservateurForm
         * @var ObservateurForm $observateurForm
         */
        $agentService = $container->get(AgentService::class);
        $campagneService = $container->get(CampagneService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $observateurService = $container->get(ObservateurService::class);
        $userService = $container->get(UserService::class);
        $importerObservateurForm = $container->get('FormElementManager')->get(ImporterObservateurForm::class);
        $observateurForm = $container->get('FormElementManager')->get(ObservateurForm::class);

        $controller = new ObservateurController();
        $controller->setAgentService($agentService);
        $controller->setCampagneService($campagneService);
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setObservateurService($observateurService);
        $controller->setUserService($userService);
        $controller->setImporterObservateurForm($importerObservateurForm);
        $controller->setObservateurForm($observateurForm);
        return $controller;
    }
}