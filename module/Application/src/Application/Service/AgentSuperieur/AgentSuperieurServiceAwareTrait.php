<?php

namespace Application\Service\AgentSuperieur;

trait AgentSuperieurServiceAwareTrait {

    private AgentSuperieurService $agentSuperieurService;

    public function getAgentSuperieurService(): AgentSuperieurService
    {
        return $this->agentSuperieurService;
    }

    public function setAgentSuperieurService(AgentSuperieurService $agentSuperieurService): void
    {
        $this->agentSuperieurService = $agentSuperieurService;
    }

}