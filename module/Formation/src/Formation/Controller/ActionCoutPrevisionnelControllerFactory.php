<?php

namespace Formation\Controller;

use Formation\Form\ActionCoutPrevisionnel\ActionCoutPrevisionnelForm;
use Formation\Service\ActionCoutPrevisionnel\ActionCoutPrevisionnelService;
use Formation\Service\Formation\FormationService;
use Formation\Service\PlanDeFormation\PlanDeFormationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ActionCoutPrevisionnelControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ActionCoutPrevisionnelController
    {
        /**
         * @see ActionCoutPrevisionnelService $actionCoutPrevisionnelService
         * @see FormationService $formationService
         * @var PlanDeFormationService $planService
         * @see ActionCoutPrevisionnelForm $actionCoutPrevisionnelForm
         */
        $actionCoutPrevisionnelService = $container->get(ActionCoutPrevisionnelService::class);
        $formationService = $container->get(FormationService::class);
        $planService = $container->get(PlanDeFormationService::class);
        $actionCoutPrevisionnelForm = $container->get('FormElementManager')->get(ActionCoutPrevisionnelForm::class);

        $controller = new ActionCoutPrevisionnelController();
        $controller->setActionCoutPrevisionnelService($actionCoutPrevisionnelService);
        $controller->setFormationService($formationService);
        $controller->setPlanDeFormationService($planService);
        $controller->setActionCoutPrevisionnelForm($actionCoutPrevisionnelForm);
        return $controller;
    }
}