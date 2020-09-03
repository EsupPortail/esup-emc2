<?php

namespace Application\Form\SelectionAgent;

trait SelectionAgentFormAwareTrait {

    /** @var SelectionAgentForm $selectionAgentForm */
    private $selectionAgentForm;

    /**
     * @return SelectionAgentForm
     */
    public function getSelectionAgentForm()
    {
        return $this->selectionAgentForm;
    }

    /**
     * @param SelectionAgentForm $selectionAgentForm
     * @return SelectionAgentForm
     */
    public function setSelectionAgentForm($selectionAgentForm)
    {
        $this->selectionAgentForm = $selectionAgentForm;
        return $this->selectionAgentForm;
    }

}