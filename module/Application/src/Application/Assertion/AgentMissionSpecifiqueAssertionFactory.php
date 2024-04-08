<?php

namespace Application\Assertion;

use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentMissionSpecifiqueAssertionFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentMissionSpecifiqueAssertion
    {
        /**
         * @var AgentMissionSpecifiqueService $agentMissionSpecifiqueService
         */
        $agentMissionSpecifiqueService = $container->get(AgentMissionSpecifiqueService::class);

        $assertion = new AgentMissionSpecifiqueAssertion();
        $assertion->setAgentMissionSpecifiqueService($agentMissionSpecifiqueService);
        return $assertion;
    }
}