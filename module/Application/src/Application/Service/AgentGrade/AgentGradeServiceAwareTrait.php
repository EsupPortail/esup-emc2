<?php

namespace Application\Service\AgentGrade;

trait AgentGradeServiceAwareTrait {

    /** @var AgentGradeService */
    private $agentGradeService;

    /**
     * @return AgentGradeService
     */
    public function getAgentGradeService(): AgentGradeService
    {
        return $this->agentGradeService;
    }

    /**
     * @param AgentGradeService $agentGradeService
     * @return AgentGradeService
     */
    public function setAgentGradeService(AgentGradeService $agentGradeService): AgentGradeService
    {
        $this->agentGradeService = $agentGradeService;
        return $this->agentGradeService;
    }

}