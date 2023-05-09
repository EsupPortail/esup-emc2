<?php

namespace Application\Form\AgentHierarchieSaisie;


trait AgentHierarchieSaisieFormAwareTrait {

    private AgentHierarchieSaisieForm $agentHierarchieSaisieForm;

    public function getAgentHierarchieSaisieForm(): AgentHierarchieSaisieForm
    {
        return $this->agentHierarchieSaisieForm;
    }

    public function setAgentHierarchieSaisieForm(AgentHierarchieSaisieForm $agentHierarchieSaisieForm): void
    {
        $this->agentHierarchieSaisieForm = $agentHierarchieSaisieForm;
    }
}