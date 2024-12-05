<?php

namespace Agent\Service\AgentQuotite;

trait AgentQuotiteServiceAwareTrait {

    private AgentQuotiteService $agentQuotiteService;

    public function getAgentQuotiteService(): AgentQuotiteService
    {
        return $this->agentQuotiteService;
    }

    public function setAgentQuotiteService(AgentQuotiteService $agentQuotiteService): void
    {
        $this->agentQuotiteService = $agentQuotiteService;
    }


}