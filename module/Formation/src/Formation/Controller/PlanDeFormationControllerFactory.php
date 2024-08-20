<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Formation\Form\PlanDeFormation\PlanDeFormationForm;
use Formation\Form\PlanDeFormationImportation\PlanDeFormationImportationForm;
use Formation\Form\SelectionFormation\SelectionFormationForm;
use Formation\Form\SelectionPlanDeFormation\SelectionPlanDeFormationForm;
use Formation\Service\Abonnement\AbonnementService;
use Formation\Service\ActionCoutPrevisionnel\ActionCoutPrevisionnelService;
use Formation\Service\Axe\AxeService;
use Formation\Service\Domaine\DomaineService;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\PlanDeFormation\PlanDeFormationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenRenderer\Service\Rendu\RenduService;

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
         * @var ActionCoutPrevisionnelService $actionCoutPrevisionnelService
         * @var AgentService $agentService
         * @var AxeService $axeService
         * @var DomaineService $domaineService
         * @var FormationService $formationService
         * @var FormationGroupeService $formationGroupeService
         * @var PlanDeFormationService $planDeFormationService
         * @var RenduService $renduService
         */
        $abonnementService = $container->get(AbonnementService::class);
        $axeService = $container->get(AxeService::class);
        $agentService = $container->get(AgentService::class);
        $actionCoutPrevisionnelService = $container->get(ActionCoutPrevisionnelService::class);
        $domaineService = $container->get(DomaineService::class);
        $formationService = $container->get(FormationService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);
        $planDeFormationService = $container->get(PlanDeFormationService::class);
        $renduService = $container->get(RenduService::class);

        /**
         * @var PlanDeFormationForm $planDeFormationForm
         * @var PlanDeFormationImportationForm $planDeFormationImportationForm
         * @var SelectionFormationForm $selectionFormationForm
         * @var SelectionPlanDeFormationForm $selectionPlanDeFormationForm
         */
        $planDeFormationForm = $container->get('FormElementManager')->get(PlanDeFormationForm::class);
        $planDeFormationImportationForm = $container->get('FormElementManager')->get(PlanDeFormationImportationForm::class);
        $selectionFormationForm = $container->get('FormElementManager')->get(SelectionFormationForm::class);
        $selectionPlanDeFormationForm = $container->get('FormElementManager')->get(SelectionPlanDeFormationForm::class);

        $controller = new PlanDeFormationController();
        $controller->setAbonnementService($abonnementService);
        $controller->setAgentService($agentService);
        $controller->setActionCoutPrevisionnelService($actionCoutPrevisionnelService);
        $controller->setAxeService($axeService);
        $controller->setDomaineService($domaineService);
        $controller->setFormationService($formationService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setPlanDeFormationService($planDeFormationService);
        $controller->setRenduService($renduService);
        $controller->setPlanDeFormationForm($planDeFormationForm);
        $controller->setPlanDeFormationImportationForm($planDeFormationImportationForm);
        $controller->setSelectionFormationForm($selectionFormationForm);
        $controller->setSelectionPlanDeFormationForm($selectionPlanDeFormationForm);
        return $controller;
    }
}