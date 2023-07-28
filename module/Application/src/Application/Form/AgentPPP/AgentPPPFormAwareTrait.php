<?php

namespace Application\Form\AgentPPP;

trait AgentPPPFormAwareTrait {

    private AgentPPPForm $agentPPPForm;

    public function getAgentPPPForm(): AgentPPPForm
    {
        return $this->agentPPPForm;
    }

    public function setAgentPPPForm(AgentPPPForm $agentPPPForm): void
    {
        $this->agentPPPForm = $agentPPPForm;
    }
}