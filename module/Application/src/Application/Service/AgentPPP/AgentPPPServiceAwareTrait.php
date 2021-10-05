<?php

namespace Application\Service\AgentPPP;

trait AgentPPPServiceAwareTrait {

    /** @var AgentPPPService */
    private $agentPPPService;

    /**
     * @return AgentPPPService
     */
    public function getAgentPPPService(): AgentPPPService
    {
        return $this->agentPPPService;
    }

    /**
     * @param AgentPPPService $agentPPPService
     * @return AgentPPPService
     */
    public function setAgentPPPService(AgentPPPService $agentPPPService): AgentPPPService
    {
        $this->agentPPPService = $agentPPPService;
        return $this->agentPPPService;
    }

}