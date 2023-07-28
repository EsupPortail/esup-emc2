<?php

namespace Application\Form\AgentStageObservation;

trait AgentStageObservationFormAwareTrait {

    private AgentStageObservationForm $agentStageObservationForm;

    public function getAgentStageObservationForm(): AgentStageObservationForm
    {
        return $this->agentStageObservationForm;
    }

    public function setAgentStageObservationForm(AgentStageObservationForm $agentStageObservationForm): void
    {
        $this->agentStageObservationForm = $agentStageObservationForm;
    }
}