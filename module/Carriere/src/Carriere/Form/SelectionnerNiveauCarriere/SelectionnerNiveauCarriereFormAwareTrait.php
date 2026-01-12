<?php

namespace Carriere\Form\SelectionnerNiveauCarriere;

trait SelectionnerNiveauCarriereFormAwareTrait {

    private SelectionnerNiveauCarriereForm $selectionnerNiveauCarriereForm;

    public function getSelectionnerNiveauCarriereForm(): SelectionnerNiveauCarriereForm
    {
        return $this->selectionnerNiveauCarriereForm;
    }

    public function setSelectionnerNiveauCarriereForm(SelectionnerNiveauCarriereForm $selectionnerNiveauCarriereForm): void
    {
        $this->selectionnerNiveauCarriereForm = $selectionnerNiveauCarriereForm;
    }

}
