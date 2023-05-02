<?php

namespace Application\Assertion;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAffectation\AgentAffectationService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\User\UserService;
use Laminas\Mvc\Application;

class AgentAffichageAssertionFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentAssertion
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function  __invoke(ContainerInterface $container): AgentAssertion
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var AgentAffectationService $agentAffectationService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var AgentAssertion $assertion */
        $assertion = new AgentAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setAgentAutoriteService($agentAutoriteService);
        $assertion->setAgentSuperieurService($agentSuperieurService);
        $assertion->setAgentAffectationService($agentAffectationService);
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent    = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);
        return $assertion;
    }
}