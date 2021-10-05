<?php

namespace Application\Service\AgentTutorat;

trait AgentTutoratServiceAwareTrait {

    /** @var AgentTutoratService */
    private $agentTutoratService;

    /**
     * @return AgentTutoratService
     */
    public function getAgentTutoratService(): AgentTutoratService
    {
        return $this->agentTutoratService;
    }

    /**
     * @param AgentTutoratService $agentTutoratService
     * @return AgentTutoratService
     */
    public function setAgentTutoratService(AgentTutoratService $agentTutoratService): AgentTutoratService
    {
        $this->agentTutoratService = $agentTutoratService;
        return $this->agentTutoratService;
    }

}