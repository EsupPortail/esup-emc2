<?php

namespace Application\Form\RessourceRh;

trait AgentStatusFormAwareTrait {

    /** @var AgentStatusForm $agentStatusForm */
    private $agentStatusForm;

    /**
     * @return AgentStatusForm
     */
    public function getAgentStatusForm()
    {
        return $this->agentStatusForm;
    }

    /**
     * @param AgentStatusForm $agentStatusForm
     * @return AgentStatusForm
     */
    public function setAgentStatusForm($agentStatusForm)
    {
        $this->agentStatusForm = $agentStatusForm;
        return $this->agentStatusForm;
    }

}