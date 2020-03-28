<?php

namespace Application\Form\SelectionCompetence;

trait SelectionCompetenceFormAwareTrait {

    /** @var SelectionCompetenceForm */
    private $selectionCompetenceForm;

    /**
     * @return SelectionCompetenceForm
     */
    public function getSelectionCompetenceForm()
    {
        return $this->selectionCompetenceForm;
    }

    /**
     * @param SelectionCompetenceForm $selectionCompetenceForm
     * @return SelectionCompetenceForm
     */
    public function setSelectionCompetenceForm($selectionCompetenceForm)
    {
        $this->selectionCompetenceForm = $selectionCompetenceForm;
        return $this->selectionCompetenceForm;
    }

}
