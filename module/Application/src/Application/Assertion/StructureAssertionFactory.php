<?php

namespace Application\Assertion;

use Application\Service\Agent\AgentService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class StructureAssertionFactory
{
    /**
     * @param ContainerInterface $container
     * @return StructureAssertion
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var StructureAssertion $assertion */
        $assertion = new StructureAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);
        return $assertion;
    }

}