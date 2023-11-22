<?php

namespace Application\Service\AgentMobilite;

trait AgentMobiliteServiceAwareTrait {

    private AgentMobiliteService $agentMobiliteService;

    public function getAgentMobiliteService(): AgentMobiliteService
    {
        return $this->agentMobiliteService;
    }

    public function setAgentMobiliteService(AgentMobiliteService $agentMobiliteService): void
    {
        $this->agentMobiliteService = $agentMobiliteService;
    }

}