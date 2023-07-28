<?php

namespace Application\Form\AgentAccompagnement;

trait AgentAccompagnementFormAwareTrait {

    private AgentAccompagnementForm $agentAccompagnementForm;

    public function getAgentAccompagnementForm(): AgentAccompagnementForm
    {
        return $this->agentAccompagnementForm;
    }

    public function setAgentAccompagnementForm(AgentAccompagnementForm $agentAccompagnementForm): void
    {
        $this->agentAccompagnementForm = $agentAccompagnementForm;
    }
}