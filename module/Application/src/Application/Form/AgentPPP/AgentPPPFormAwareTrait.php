<?php

namespace Application\Form\AgentPPP;

trait AgentPPPFormAwareTrait {

    /** @var AgentPPPForm */
    private $agentPPPForm;

    /**
     * @return AgentPPPForm
     */
    public function getAgentPPPForm(): AgentPPPForm
    {
        return $this->agentPPPForm;
    }

    /**
     * @param AgentPPPForm $agentPPPForm
     * @return AgentPPPForm
     */
    public function setAgentPPPForm(AgentPPPForm $agentPPPForm): AgentPPPForm
    {
        $this->agentPPPForm = $agentPPPForm;
        return $this->agentPPPForm;
    }
}