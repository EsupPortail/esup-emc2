<?php

namespace Application\Service\AgentQuotite;

trait AgentQuotiteServiceAwareTrait {

    /** @var AgentQuotiteService $agentQuotiteService */
    private $agentQuotiteService;

    /**
     * @return AgentQuotiteService
     */
    public function getAgentQuotiteService(): AgentQuotiteService
    {
        return $this->agentQuotiteService;
    }

    /**
     * @param AgentQuotiteService $agentQuotiteService
     * @return AgentQuotiteService
     */
    public function setAgentQuotiteService(AgentQuotiteService $agentQuotiteService): AgentQuotiteService
    {
        $this->agentQuotiteService = $agentQuotiteService;
        return $this->agentQuotiteService;
    }


}