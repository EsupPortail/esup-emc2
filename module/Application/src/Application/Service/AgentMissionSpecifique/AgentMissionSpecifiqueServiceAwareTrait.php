<?php

namespace Application\Service\AgentMissionSpecifique;

trait AgentMissionSpecifiqueServiceAwareTrait {

    /** @var AgentMissionSpecifiqueService */
    private $agentMissionSpecifiqueService;

    /**
     * @return AgentMissionSpecifiqueService
     */
    public function getAgentMissionSpecifiqueService(): AgentMissionSpecifiqueService
    {
        return $this->agentMissionSpecifiqueService;
    }

    /**
     * @param AgentMissionSpecifiqueService $agentMissionSpecifiqueService
     * @return AgentMissionSpecifiqueService
     */
    public function setAgentMissionSpecifiqueService(AgentMissionSpecifiqueService $agentMissionSpecifiqueService): AgentMissionSpecifiqueService
    {
        $this->agentMissionSpecifiqueService = $agentMissionSpecifiqueService;
        return $this->agentMissionSpecifiqueService;
    }

}