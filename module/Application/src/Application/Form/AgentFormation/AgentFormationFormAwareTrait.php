<?php

namespace Application\Form\AgentFormation;

trait AgentFormationFormAwareTrait {

    /** @var AgentFormationForm $agentFormationForm */
    private $agentFormationForm;

    /**
     * @return AgentFormationForm
     */
    public function getAgentFormationForm()
    {
        return $this->agentFormationForm;
    }

    /**
     * @param AgentFormationForm $agentFormationForm
     * @return AgentFormationForm
     */
    public function setAgentFormationForm($agentFormationForm)
    {
        $this->agentFormationForm = $agentFormationForm;
        return $this->agentFormationForm;
    }


}