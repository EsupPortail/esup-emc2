<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\AgentForceSansObligation\AgentForceSansObligationForm;
use EntretienProfessionnel\Service\AgentForceSansObligation\AgentForceSansObligationService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentForceSansObligationControllerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentForceSansObligationController
    {
        /**
         * @var AgentForceSansObligationService $agentForceSansObligationService
         * @var CampagneService $campagneService
         * @var AgentForceSansObligationForm $agentForceSansObligationForm
         */
        $agentForceSansObligationService = $container->get(AgentForceSansObligationService::class);
        $campagneService = $container->get(CampagneService::class);
        $agentForceSansObligationForm = $container->get('FormElementManager')->get(AgentForceSansObligationForm::class);

        $controller = new AgentForceSansObligationController();
        $controller->setAgentForceSansObligationService($agentForceSansObligationService);
        $controller->setCampagneService($campagneService);
        $controller->setAgentForceSansObligationForm($agentForceSansObligationForm);
        return $controller;
    }
}