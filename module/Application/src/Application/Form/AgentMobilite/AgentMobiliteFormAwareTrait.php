<?php

namespace Application\Form\AgentMobilite;

trait AgentMobiliteFormAwareTrait {

    private AgentMobiliteForm $agentMobiliteForm;

    public function getAgentMobiliteForm(): AgentMobiliteForm
    {
        return $this->agentMobiliteForm;
    }

    public function setAgentMobiliteForm(AgentMobiliteForm $agentMobiliteForm): void
    {
        $this->agentMobiliteForm = $agentMobiliteForm;
    }
}