<?php

namespace Agent\Service\AgentRef;

trait AgentRefServiceAwareTrait
{
    private AgentRefService $agentRefService;

    public function getAgentRefService(): AgentRefService
    {
        return $this->agentRefService;
    }

    public function setAgentRefService(AgentRefService $agentRefService): void
    {
        $this->agentRefService = $agentRefService;
    }
}