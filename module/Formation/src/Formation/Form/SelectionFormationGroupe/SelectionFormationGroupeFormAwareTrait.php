<?php

namespace Formation\Form\SelectionFormationGroupe;

trait SelectionFormationGroupeFormAwareTrait
{

    private SelectionFormationGroupeForm $selectionFormationGroupeForm;

    public function getSelectionFormationGroupeForm() : SelectionFormationGroupeForm
    {
        return $this->selectionFormationGroupeForm;
    }

    public function setSelectionFormationGroupeForm(SelectionFormationGroupeForm $selectionFormationGroupeForm) : void
    {
        $this->selectionFormationGroupeForm = $selectionFormationGroupeForm;
    }

}
