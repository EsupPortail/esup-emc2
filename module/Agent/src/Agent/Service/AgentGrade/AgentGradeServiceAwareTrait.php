<?php

namespace Agent\Service\AgentGrade;

trait AgentGradeServiceAwareTrait {

    private AgentGradeService $agentGradeService;

    public function getAgentGradeService(): AgentGradeService
    {
        return $this->agentGradeService;
    }

    public function setAgentGradeService(AgentGradeService $agentGradeService): void
    {
        $this->agentGradeService = $agentGradeService;
    }

}