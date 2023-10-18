<?php

namespace EntretienProfessionnel\Service\AgentForceSansObligation;

trait AgentForceSansObligationServiceAwareTrait {

    private AgentForceSansObligationService $agentForceSansObligationService;

    public function getAgentForceSansObligationService(): AgentForceSansObligationService
    {
        return $this->agentForceSansObligationService;
    }

    public function setAgentForceSansObligationService(AgentForceSansObligationService $agentForceSansObligationService): void
    {
        $this->agentForceSansObligationService = $agentForceSansObligationService;
    }

}