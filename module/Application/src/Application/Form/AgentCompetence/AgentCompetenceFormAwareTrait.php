<?php

namespace Application\Form\AgentCompetence;

trait AgentCompetenceFormAwareTrait {

    /** @var AgentCompetenceForm $agentCompetenceForm */
    private $agentCompetenceForm;

    /**
     * @return AgentCompetenceForm
     */
    public function getAgentCompetenceForm()
    {
        return $this->agentCompetenceForm;
    }

    /**
     * @param AgentCompetenceForm $agentCompetenceForm
     * @return AgentCompetenceForm
     */
    public function setAgentCompetenceForm($agentCompetenceForm)
    {
        $this->agentCompetenceForm = $agentCompetenceForm;
        return $this->agentCompetenceForm;
    }


}