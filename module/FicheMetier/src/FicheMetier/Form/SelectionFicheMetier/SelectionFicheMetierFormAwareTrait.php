<?php

namespace FicheMetier\Form\SelectionFicheMetier;

trait SelectionFicheMetierFormAwareTrait {

    private SelectionFicheMetierForm $selectionFicheMetierForm;

    public function getSelectionFicheMetierForm(): SelectionFicheMetierForm
    {
        return $this->selectionFicheMetierForm;
    }

    public function setSelectionFicheMetierForm(SelectionFicheMetierForm $selectionFicheMetierForm): void
    {
        $this->selectionFicheMetierForm = $selectionFicheMetierForm;
    }

}