<?php

namespace Application\Service\Agent;

trait AgentServiceAwareTrait {

    /** @var AgentService */
    private $agentService;

    /**
     * @return AgentService
     */
    public function getAgentService()
    {
        return $this->agentService;
    }

    /**
     * @param AgentService $agentService
     * @return AgentService
     */
    public function setAgentService($agentService)
    {
        $this->agentService = $agentService;
        return $this->agentService;
    }

}