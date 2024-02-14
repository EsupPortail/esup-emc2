<?php

namespace Formation\Form\SelectionFormateur;

trait SelectionFormateurFormAwareTrait
{

    private SelectionFormateurForm $selectionFormateurForm;

    public function getSelectionFormateurForm(): SelectionFormateurForm
    {
        return $this->selectionFormateurForm;
    }

    public function setSelectionFormateurForm(SelectionFormateurForm $selectionFormateurForm): void
    {
        $this->selectionFormateurForm = $selectionFormateurForm;
    }

}
