<?php

namespace EmploiRepere\Controller;

use Agent\Service\AgentPoste\AgentPosteService;
use EmploiRepere\Form\EmploiRepere\EmploiRepereForm;
use EmploiRepere\Service\EmploiRepere\EmploiRepereService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class EmploiRepereControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EmploiRepereController
    {
        /**
         * @var AgentPosteService $agentPosteService
         * @var EmploiRepereService $emploiRepereService
         * @var EmploiRepereForm $emploiRepereForm
         */
        $agentPosteService = $container->get(AgentPosteService::class);
        $emploiRepereService = $container->get(EmploiRepereService::class);
        $emploiRepereForm = $container->get('FormElementManager')->get(EmploiRepereForm::class);

        $controller = new EmploiRepereController();
        $controller->setAgentPosteService($agentPosteService);
        $controller->setEmploiRepereService($emploiRepereService);
        $controller->setEmploiRepereForm($emploiRepereForm);
        return $controller;
    }
}
