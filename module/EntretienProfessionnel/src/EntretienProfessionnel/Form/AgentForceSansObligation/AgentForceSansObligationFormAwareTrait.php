<?php

namespace EntretienProfessionnel\Form\AgentForceSansObligation;

trait AgentForceSansObligationFormAwareTrait {

    private AgentForceSansObligationForm $agentForceSansObligationForm;

    public function getAgentForceSansObligationForm(): AgentForceSansObligationForm
    {
        return $this->agentForceSansObligationForm;
    }

    public function setAgentForceSansObligationForm(AgentForceSansObligationForm $agentForceSansObligationForm): void
    {
        $this->agentForceSansObligationForm = $agentForceSansObligationForm;
    }

}