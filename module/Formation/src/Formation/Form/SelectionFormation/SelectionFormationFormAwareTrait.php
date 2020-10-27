<?php

namespace Formation\Form\SelectionFormation;

trait SelectionFormationFormAwareTrait {

    /** @var SelectionFormationForm */
    private $selectionFormationForm;

    /**
     * @return SelectionFormationForm
     */
    public function getSelectionFormationForm()
    {
        return $this->selectionFormationForm;
    }

    /**
     * @param SelectionFormationForm $selectionFormationForm
     * @return SelectionFormationForm
     */
    public function setSelectionFormationForm(SelectionFormationForm $selectionFormationForm)
    {
        $this->selectionFormationForm = $selectionFormationForm;
        return $this->selectionFormationForm;
    }

}
