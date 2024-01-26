<?php

namespace Application\Service\Agent;

trait AgentServiceAwareTrait {

    private AgentService $agentService;

    public function getAgentService(): AgentService
    {
        return $this->agentService;
    }

    public function setAgentService(AgentService $agentService): void
    {
        $this->agentService = $agentService;
    }

}