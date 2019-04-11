<?php

namespace Application\Form\Agent;

trait AgentImportFormAwareTrait {

    /** @var AgentImportForm $agentImportForm */
    private $agentImportForm;

    /**
     * @return AgentImportForm
     */
    public function getAgentImportForm()
    {
        return $this->agentImportForm;
    }

    /**
     * @param AgentImportForm $agentImportForm
     * @return AgentImportForm
     */
    public function setAgentImportForm($agentImportForm)
    {
        $this->agentImportForm = $agentImportForm;
        return $this->agentImportForm;
    }


}