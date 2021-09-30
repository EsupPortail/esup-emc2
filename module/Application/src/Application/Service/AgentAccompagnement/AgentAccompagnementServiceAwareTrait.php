<?php

namespace Application\Service\AgentAccompagnement;

trait AgentAccompagnementServiceAwareTrait {

    /** @var AgentAccompagnementService */
    private $agentAccompagnementService;

    /**
     * @return AgentAccompagnementService
     */
    public function getAgentAccompagnementService(): AgentAccompagnementService
    {
        return $this->agentAccompagnementService;
    }

    /**
     * @param AgentAccompagnementService $agentAccompagnementService
     * @return AgentAccompagnementService
     */
    public function setAgentAccompagnementService(AgentAccompagnementService $agentAccompagnementService): AgentAccompagnementService
    {
        $this->agentAccompagnementService = $agentAccompagnementService;
        return $this->agentAccompagnementService;
    }

}