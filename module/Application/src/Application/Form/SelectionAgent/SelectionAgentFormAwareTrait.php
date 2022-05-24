<?php

namespace Application\Form\SelectionAgent;

trait SelectionAgentFormAwareTrait {

    /** @var SelectionAgentForm $selectionAgentForm */
    private $selectionAgentForm;

    /**
     * @return SelectionAgentForm
     */
    public function getSelectionAgentForm() : SelectionAgentForm
    {
        return $this->selectionAgentForm;
    }

    /**
     * @param SelectionAgentForm $selectionAgentForm
     * @return SelectionAgentForm
     */
    public function setSelectionAgentForm(SelectionAgentForm $selectionAgentForm) : SelectionAgentForm
    {
        $this->selectionAgentForm = $selectionAgentForm;
        return $this->selectionAgentForm;
    }

}