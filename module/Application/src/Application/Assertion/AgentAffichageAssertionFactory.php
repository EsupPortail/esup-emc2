<?php

namespace Application\Assertion;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAffectation\AgentAffectationService;
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
         * @var AgentAffectationService $agentAffectationService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var AgentAssertion $assertion */
        $assertion = new AgentAssertion();
        $assertion->setAgentService($agentService);
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