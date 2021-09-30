<?php

namespace Application\Form\AgentAccompagnement;

trait AgentAccompagnementFormAwareTrait {

    /** @var AgentAccompagnementForm */
    private $agentAccompagnementForm;

    /**
     * @return AgentAccompagnementForm
     */
    public function getAgentAccompagnementForm(): AgentAccompagnementForm
    {
        return $this->agentAccompagnementForm;
    }

    /**
     * @param AgentAccompagnementForm $agentAccompagnementForm
     * @return AgentAccompagnementForm
     */
    public function setAgentAccompagnementForm(AgentAccompagnementForm $agentAccompagnementForm): AgentAccompagnementForm
    {
        $this->agentAccompagnementForm = $agentAccompagnementForm;
        return $this->agentAccompagnementForm;
    }
}