<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Formation\Form\Abonnement\AbonnementForm;
use Formation\Service\Abonnement\AbonnementService;
use Formation\Service\Formation\FormationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AbonnementControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return AbonnementController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AbonnementController
    {
        /**
         * @var AbonnementService $abonnementService
         * @var AgentService $agentService
         * @var FormationService $formationService
         */
        $abonnementService = $container->get(AbonnementService::class);
        $agentService = $container->get(AgentService::class);
        $formationService = $container->get(FormationService::class);

        /**
         * @var AbonnementForm $abonnementForm
         */
        $abonnementForm = $container->get('FormElementManager')->get(AbonnementForm::class);

        $controller = new AbonnementController();
        $controller->setAbonnementService($abonnementService);
        $controller->setAgentService($agentService);
        $controller->setFormationService($formationService);
        $controller->setAbonnementForm($abonnementForm);

        return $controller;
    }
}