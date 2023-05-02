<?php

namespace Application\Form\AgentHierarchieImportation;

trait AgentHierarchieImportationFormAwareTrait {

    private AgentHierarchieImportationForm $agentHierarchieImportationForm;

    public function getAgentHierarchieImportationForm(): AgentHierarchieImportationForm
    {
        return $this->agentHierarchieImportationForm;
    }

    public function setAgentHierarchieImportationForm(AgentHierarchieImportationForm $agentHierarchieImportationForm): void
    {
        $this->agentHierarchieImportationForm = $agentHierarchieImportationForm;
    }

}