<?php

namespace Agent\Assertion;

use Agent\Service\Agent\AgentService;
use Agent\Service\AgentAffectation\AgentAffectationService;
use Agent\Service\AgentAutorite\AgentAutoriteService;
use Agent\Service\AgentSuperieur\AgentSuperieurService;
use Interop\Container\ContainerInterface;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Structure\StructureService;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenUtilisateur\Service\User\UserService;

class PortfolioAssertionFactory
{

    /**
     * @param ContainerInterface $container
     * @return AgentAssertion
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PortfolioAssertion
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var AgentAffectationService $agentAffectationService
         * @var ObservateurService $observateurService
         * @var PrivilegeService $privilegeService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $observateurService = $container->get(ObservateurService::class);
        $privilegeService = $container->get(PrivilegeService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        $assertion = new PortfolioAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setAgentAutoriteService($agentAutoriteService);
        $assertion->setAgentSuperieurService($agentSuperieurService);
        $assertion->setAgentAffectationService($agentAffectationService);
        $assertion->setObservateurService($observateurService);
        $assertion->setPrivilegeService($privilegeService);
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);
        return $assertion;
    }
}
