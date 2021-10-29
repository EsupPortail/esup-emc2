<?php

namespace Application\Service\AgentStatut;

trait AgentStatutServiceAwareTrait {

    /** @var AgentStatutService */
    private $agentStatutService;

    /**
     * @return AgentStatutService
     */
    public function getAgentStatutService(): AgentStatutService
    {
        return $this->agentStatutService;
    }

    /**
     * @param AgentStatutService $agentStatutService
     * @return AgentStatutService
     */
    public function setAgentStatutService(AgentStatutService $agentStatutService): AgentStatutService
    {
        $this->agentStatutService = $agentStatutService;
        return $this->agentStatutService;
    }

}