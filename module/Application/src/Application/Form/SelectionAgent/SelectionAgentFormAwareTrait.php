<?php

namespace Application\Form\SelectionAgent;

trait SelectionAgentFormAwareTrait {

    private SelectionAgentForm $selectionAgentForm;

    public function getSelectionAgentForm() : SelectionAgentForm
    {
        return $this->selectionAgentForm;
    }

    public function setSelectionAgentForm(SelectionAgentForm $selectionAgentForm) : void
    {
        $this->selectionAgentForm = $selectionAgentForm;
    }

}