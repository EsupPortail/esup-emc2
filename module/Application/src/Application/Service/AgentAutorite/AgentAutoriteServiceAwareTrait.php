<?php

namespace Application\Service\AgentAutorite;

trait AgentAutoriteServiceAwareTrait {

    private AgentAutoriteService $agentAutoriteService;

    public function getAgentAutoriteService(): AgentAutoriteService
    {
        return $this->agentAutoriteService;
    }

    public function setAgentAutoriteService(AgentAutoriteService $agentAutoriteService): void
    {
        $this->agentAutoriteService = $agentAutoriteService;
    }

}