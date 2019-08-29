<?php

namespace Application\Form\AgentMissionSpecifique;

trait AgentMissionSpecifiqueFormAwareTrait {

    /** @var AgentMissionSpecifiqueForm $agentMissionSpecifiqueForm */
    private $agentMissionSpecifiqueForm;

    /**
     * @return AgentMissionSpecifiqueForm
     */
    public function getAgentMissionSpecifiqueForm()
    {
        return $this->agentMissionSpecifiqueForm;
    }

    /**
     * @param AgentMissionSpecifiqueForm $agentMissionSpecifiqueForm
     * @return AgentMissionSpecifiqueForm
     */
    public function setAgentMissionSpecifiqueForm($agentMissionSpecifiqueForm)
    {
        $this->agentMissionSpecifiqueForm = $agentMissionSpecifiqueForm;
        return $this->agentMissionSpecifiqueForm;
    }


}