<?php

namespace Application\Form\AgentTutorat;

trait AgentTutoratFormAwareTrait {

    /** @var AgentTutoratForm */
    private $agentTutoratForm;

    /**
     * @return AgentTutoratForm
     */
    public function getAgentTutoratForm(): AgentTutoratForm
    {
        return $this->agentTutoratForm;
    }

    /**
     * @param AgentTutoratForm $agentTutoratForm
     * @return AgentTutoratForm
     */
    public function setAgentTutoratForm(AgentTutoratForm $agentTutoratForm): AgentTutoratForm
    {
        $this->agentTutoratForm = $agentTutoratForm;
        return $this->agentTutoratForm;
    }
}