<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Formation\Service\FormationInstance\FormationInstanceService;
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
         * @var EtatTypeService $etatTypeService
         * @var FormationInstanceService $formationInstanceService
         * @var RenduService $renduService
         * @var StagiaireExterneService $stagiaireExterneService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $renduService = $container->get(RenduService::class);
        $stagiaireExterneService = $container->get(StagiaireExterneService::class);
        $userService = $container->get(UserService::class);

        $controller = new IndexController();
        $controller->setAgentService($agentService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setRenduService($renduService);
        $controller->setStagiaireExterneService($stagiaireExterneService);
        $controller->setUserService($userService);
        return $controller;
    }
}