<?php

namespace Application\Service\AgentStageObservation;

trait AgentStageObservationServiceAwareTrait {

    /** @var AgentStageObservationService */
    private $agentStageObservationService;

    /**
     * @return AgentStageObservationService
     */
    public function getAgentStageObservationService(): AgentStageObservationService
    {
        return $this->agentStageObservationService;
    }

    /**
     * @param AgentStageObservationService $agentStageObservationService
     * @return AgentStageObservationService
     */
    public function setAgentStageObservationService(AgentStageObservationService $agentStageObservationService): AgentStageObservationService
    {
        $this->agentStageObservationService = $agentStageObservationService;
        return $this->agentStageObservationService;
    }

}