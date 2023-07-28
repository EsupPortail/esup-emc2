<?php

namespace Formation\Form\SelectionFormation;

trait SelectionFormationFormAwareTrait
{

    private SelectionFormationForm $selectionFormationForm;

    public function getSelectionFormationForm(): SelectionFormationForm
    {
        return $this->selectionFormationForm;
    }

    public function setSelectionFormationForm(SelectionFormationForm $selectionFormationForm): void
    {
        $this->selectionFormationForm = $selectionFormationForm;
    }

}
