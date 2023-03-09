<?php

namespace Metier\Form\SelectionnerDomaines;

trait SelectionnerDomainesFormAwareTrait {

    private SelectionnerDomainesForm $selectionnerDomainesForm;

    public function getSelectionnerDomainesForm(): SelectionnerDomainesForm
    {
        return $this->selectionnerDomainesForm;
    }

    public function setSelectionnerDomainesForm(SelectionnerDomainesForm $selectionnerDomainesForm): void
    {
        $this->selectionnerDomainesForm = $selectionnerDomainesForm;
    }

}