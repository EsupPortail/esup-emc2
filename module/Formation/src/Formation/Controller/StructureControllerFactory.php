<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Formation\Service\DemandeExterne\DemandeExterneService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

class StructureControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return StructureController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : StructureController
    {
        /**
         * @var AgentService $agentService
         * @var DemandeExterneService $demandeExterneService
         * @var FormationInstanceInscritService $instanceService
         * @var StructureService $structureService
         */
        $agentService = $container->get(AgentService::class);
        $demandeExterneService = $container->get(DemandeExterneService::class);
        $instanceService = $container->get(FormationInstanceInscritService::class);
        $structureService = $container->get(StructureService::class);

        $controller = new StructureController();
        $controller->setAgentService($agentService);
        $controller->setFormationInstanceInscritService($instanceService);
        $controller->setDemandeExterneService($demandeExterneService);
        $controller->setStructureService($structureService);
        return $controller;
    }
}