<?php

namespace Application\Form\AgentApplication;

trait AgentApplicationFormAwareTrait {

    /** @var AgentApplicationForm */
    private $agentApplicationForm;

    /**
     * @return AgentApplicationForm
     */
    public function getAgentApplicationForm()
    {
        return $this->agentApplicationForm;
    }

    /**
     * @param AgentApplicationForm $agentApplicationForm
     * @return AgentApplicationForm
     */
    public function setAgentApplicationForm($agentApplicationForm)
    {
        $this->agentApplicationForm = $agentApplicationForm;
        return $this->agentApplicationForm;
    }

}