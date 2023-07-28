<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Formation\Service\Abonnement\AbonnementService;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\PlanDeFormation\PlanDeFormationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PlanFormationControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return PlanFormationController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PlanFormationController
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

        $controller = new PlanFormationController();
        $controller->setAbonnementService($abonnementService);
        $controller->setAgentService($agentService);
        $controller->setFormationService($formationService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setPlanDeFormationService($planDeFormationService);
        return $controller;
    }
}