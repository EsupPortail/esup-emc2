<?php

namespace Application\Form\Agent;

trait AgentFormAwareTrait {

    /** @var AgentForm */
    private  $agentForm;

    /**
     * @return AgentForm
     */
    public function getAgentForm()
    {
        return $this->agentForm;
    }

    /**
     * @param AgentForm $agentForm
     * @return AgentForm
     */
    public function setAgentForm($agentForm)
    {
        $this->agentForm = $agentForm;
        return $this->agentForm;
    }


}