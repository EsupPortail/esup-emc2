<?php

namespace Application\Form\AgentHierarchieCalcul;

trait AgentHierarchieCalculFormAwareTrait {

    private AgentHierarchieCalculForm $agentHierarchieCalculForm;

    public function getAgentHierarchieCalculForm(): AgentHierarchieCalculForm
    {
        return $this->agentHierarchieCalculForm;
    }

    public function setAgentHierarchieCalculForm(AgentHierarchieCalculForm $agentHierarchieCalculForm): void
    {
        $this->agentHierarchieCalculForm = $agentHierarchieCalculForm;
    }

}