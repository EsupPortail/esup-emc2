<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Formation\Service\DemandeExterne\DemandeExterneService;
use Formation\Service\Session\SessionService;
use Formation\Service\StagiaireExterne\StagiaireExterneService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenUtilisateur\Service\User\UserService;

class IndexControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return IndexController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): IndexController
    {
        /**
         * @var AgentService $agentService
         * @var DemandeExterneService $demandeExterneService
         * @var EtatTypeService $etatTypeService
         * @var SessionService $sessionService
         * @var RenduService $renduService
         * @var StagiaireExterneService $stagiaireExterneService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $demandeExterneService = $container->get(DemandeExterneService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $sessionService = $container->get(SessionService::class);
        $renduService = $container->get(RenduService::class);
        $stagiaireExterneService = $container->get(StagiaireExterneService::class);
        $userService = $container->get(UserService::class);

        $controller = new IndexController();
        $controller->setAgentService($agentService);
        $controller->setDemandeExterneService($demandeExterneService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setSessionService($sessionService);
        $controller->setRenduService($renduService);
        $controller->setStagiaireExterneService($stagiaireExterneService);
        $controller->setUserService($userService);
        return $controller;
    }
}