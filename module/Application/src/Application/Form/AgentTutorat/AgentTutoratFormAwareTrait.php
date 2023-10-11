<?php

namespace Application\Form\AgentTutorat;

trait AgentTutoratFormAwareTrait {

    private AgentTutoratForm $agentTutoratForm;

    public function getAgentTutoratForm(): AgentTutoratForm
    {
        return $this->agentTutoratForm;
    }

    public function setAgentTutoratForm(AgentTutoratForm $agentTutoratForm): void
    {
        $this->agentTutoratForm = $agentTutoratForm;
    }
}