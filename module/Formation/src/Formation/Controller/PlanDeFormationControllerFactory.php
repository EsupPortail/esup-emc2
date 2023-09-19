<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Formation\Form\PlanDeFormation\PlanDeFormationForm;
use Formation\Form\SelectionFormation\SelectionFormationForm;
use Formation\Form\SelectionPlanDeFormation\SelectionPlanDeFormationForm;
use Formation\Service\Abonnement\AbonnementService;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\PlanDeFormation\PlanDeFormationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PlanDeFormationControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return PlanDeFormationController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PlanDeFormationController
    {
        /**
         * @var AbonnementService $abonnementService
         * @var AgentService $agentService
         * @var FormationService $formationService
         * @var FormationGroupeService $formationGroupeService
         * @var FormationInstanceService $formationInstanceService
         * @var PlanDeFormationService $planDeFormationService
         */
        $abonnementService = $container->get(AbonnementService::class);
        $agentService = $container->get(AgentService::class);
        $formationService = $container->get(FormationService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $planDeFormationService = $container->get(PlanDeFormationService::class);

        /**
         * @var PlanDeFormationForm $planDeFormationForm
         * @var SelectionFormationForm $selectionFormationForm
         * @var SelectionPlanDeFormationForm $selectionPlanDeFormationForm
         */
        $planDeFormationForm = $container->get('FormElementManager')->get(PlanDeFormationForm::class);
        $selectionFormationForm = $container->get('FormElementManager')->get(SelectionFormationForm::class);
        $selectionPlanDeFormationForm = $container->get('FormElementManager')->get(SelectionPlanDeFormationForm::class);

        $controller = new PlanDeFormationController();
        $controller->setAbonnementService($abonnementService);
        $controller->setAgentService($agentService);
        $controller->setFormationService($formationService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setPlanDeFormationService($planDeFormationService);
        $controller->setPlanDeFormationForm($planDeFormationForm);
        $controller->setSelectionFormationForm($selectionFormationForm);
        $controller->setSelectionPlanDeFormationForm($selectionPlanDeFormationForm);
        return $controller;
    }
}