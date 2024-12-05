<?php

namespace Agent\Service\AgentStatut;

trait AgentStatutServiceAwareTrait {

    private AgentStatutService $agentStatutService;

    public function getAgentStatutService(): AgentStatutService
    {
        return $this->agentStatutService;
    }

    public function setAgentStatutService(AgentStatutService $agentStatutService): void
    {
        $this->agentStatutService = $agentStatutService;
    }

}