<?php

namespace Metier\Form\SelectionnerMetier;

trait SelectionnerMetierFormAwareTrait {

    private SelectionnerMetierForm $selectionnerMetierForm;

    public function getSelectionnerMetierForm() : SelectionnerMetierForm
    {
        return $this->selectionnerMetierForm;
    }

    public function setSelectionnerMetierForm(SelectionnerMetierForm $selectionnerMetierForm) : void
    {
        $this->selectionnerMetierForm = $selectionnerMetierForm;
    }

}