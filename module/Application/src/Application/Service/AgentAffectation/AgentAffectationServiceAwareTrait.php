<?php

namespace Application\Service\AgentAffectation;

trait AgentAffectationServiceAwareTrait {

    /** @var AgentAffectationService */
    private $agentAffectationService;

    /**
     * @return AgentAffectationService
     */
    public function getAgentAffectationService(): AgentAffectationService
    {
        return $this->agentAffectationService;
    }

    /**
     * @param AgentAffectationService $agentAffectationService
     * @return AgentAffectationService
     */
    public function setAgentAffectationService(AgentAffectationService $agentAffectationService): AgentAffectationService
    {
        $this->agentAffectationService = $agentAffectationService;
        return $this->agentAffectationService;
    }

}