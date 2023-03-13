<?php

namespace Application\Service\AgentPoste;

trait AgentPosteServiceAwareTrait {

    private AgentPosteService $agentPosteService;

    public function getAgentPosteService(): AgentPosteService
    {
        return $this->agentPosteService;
    }

    public function setAgentPosteService(AgentPosteService $agentPosteService): void
    {
        $this->agentPosteService = $agentPosteService;
    }

}