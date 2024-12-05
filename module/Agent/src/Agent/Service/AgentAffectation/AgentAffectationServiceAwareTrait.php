<?php

namespace Agent\Service\AgentAffectation;

trait AgentAffectationServiceAwareTrait {

    private AgentAffectationService $agentAffectationService;

    public function getAgentAffectationService(): AgentAffectationService
    {
        return $this->agentAffectationService;
    }

    public function setAgentAffectationService(AgentAffectationService $agentAffectationService): void
    {
        $this->agentAffectationService = $agentAffectationService;
    }

}