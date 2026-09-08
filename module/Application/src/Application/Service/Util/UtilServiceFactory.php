<?php

namespace Application\Service\Util;

use Agent\Service\Agent\AgentService;
use Agent\Service\AgentAutorite\AgentAutoriteService;
use Agent\Service\AgentSuperieur\AgentSuperieurService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\User\UserService;

class UtilServiceFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): UtilService
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        $service = new UtilService();
        $service->setAgentService($agentService);
        $service->setAgentAutoriteService($agentAutoriteService);
        $service->setAgentSuperieurService($agentSuperieurService);
        $service->setEntretienProfessionnelService($entretienProfessionnelService);
        $service->setStructureService($structureService);
        $service->setUserService($userService);
        return $service;
    }
}
