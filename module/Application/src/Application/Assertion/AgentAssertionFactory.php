<?php

namespace Application\Assertion;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\User\UserService;
use Laminas\Mvc\Application;

class AgentAssertionFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentAssertion
     */
    public function  __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var AgentAssertion $assertion */
        $assertion = new AgentAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent    = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);
        return $assertion;
    }
}