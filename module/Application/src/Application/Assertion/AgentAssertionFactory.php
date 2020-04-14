<?php

namespace Application\Assertion;

use Interop\Container\ContainerInterface;

class AgentAssertionFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentAssertion
     */
    public function  __invoke(ContainerInterface $container)
    {
        /** @var AgentAssertion $assertion */
        $assertion = new AgentAssertion();
        return $assertion;
    }
}