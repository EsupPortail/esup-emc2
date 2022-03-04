<?php

namespace Application\Form\SelectionFicheMetier;

trait SelectionFicheMetierFormAwareTrait {

    /** @var SelectionFicheMetierForm */
    private $selectionFicheMetierForm;

    /**
     * @return SelectionFicheMetierForm
     */
    public function getSelectionFicheMetierForm(): SelectionFicheMetierForm
    {
        return $this->selectionFicheMetierForm;
    }

    /**
     * @param SelectionFicheMetierForm $selectionFicheMetierForm
     * @return SelectionFicheMetierForm
     */
    public function setSelectionFicheMetierForm(SelectionFicheMetierForm $selectionFicheMetierForm): SelectionFicheMetierForm
    {
        $this->selectionFicheMetierForm = $selectionFicheMetierForm;
        return $this->selectionFicheMetierForm;
    }

}