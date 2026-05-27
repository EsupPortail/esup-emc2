<?php

namespace Agent\Service\AgentEmploiType;

trait AgentEmploiTypeServiceAwareTrait
{
    private AgentEmploiTypeService $agentEmploiTypeService;

    public function getAgentEmploiTypeService(): AgentEmploiTypeService
    {
        return $this->agentEmploiTypeService;
    }

    public function setAgentEmploiTypeService(AgentEmploiTypeService $agentEmploiTypeService): void
    {
        $this->agentEmploiTypeService = $agentEmploiTypeService;
    }

}
