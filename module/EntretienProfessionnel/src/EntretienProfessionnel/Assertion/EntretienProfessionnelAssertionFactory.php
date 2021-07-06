<?php

namespace EntretienProfessionnel\Assertion;

use Application\Service\Agent\AgentService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class EntretienProfessionnelAssertionFactory {

    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelAssertion
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

        /** @var EntretienProfessionnelAssertion $assertion */
        $assertion = new EntretienProfessionnelAssertion();
        $assertion->setAgentService($agentService);
        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);

        return $assertion;
    }
}