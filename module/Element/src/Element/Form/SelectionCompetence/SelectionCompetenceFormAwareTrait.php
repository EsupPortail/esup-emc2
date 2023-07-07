<?php

namespace Element\Form\SelectionCompetence;

trait SelectionCompetenceFormAwareTrait {

    private SelectionCompetenceForm $selectionCompetenceForm;

    public function getSelectionCompetenceForm() : SelectionCompetenceForm
    {
        return $this->selectionCompetenceForm;
    }

    public function setSelectionCompetenceForm(SelectionCompetenceForm $selectionCompetenceForm): void
    {
        $this->selectionCompetenceForm = $selectionCompetenceForm;
    }

}
