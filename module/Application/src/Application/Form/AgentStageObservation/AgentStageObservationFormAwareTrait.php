<?php

namespace Application\Form\AgentStageObservation;

trait AgentStageObservationFormAwareTrait {

    /** @var AgentStageObservationForm */
    private $agentStageObservationForm;

    /**
     * @return AgentStageObservationForm
     */
    public function getAgentStageObservationForm(): AgentStageObservationForm
    {
        return $this->agentStageObservationForm;
    }

    /**
     * @param AgentStageObservationForm $agentStageObservationForm
     * @return AgentStageObservationForm
     */
    public function setAgentStageObservationForm(AgentStageObservationForm $agentStageObservationForm): AgentStageObservationForm
    {
        $this->agentStageObservationForm = $agentStageObservationForm;
        return $this->agentStageObservationForm;
    }
}