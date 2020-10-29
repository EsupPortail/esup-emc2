<?php

namespace UnicaenEtat\Form\SelectionEtat;

trait SelectionEtatFormAwareTrait {

    /** @var SelectionEtatForm */
    private $selectionEtatForm;

    /**
     * @return SelectionEtatForm
     */
    public function getSelectionEtatForm(): SelectionEtatForm
    {
        return $this->selectionEtatForm;
    }

    /**
     * @param SelectionEtatForm $selectionEtatForm
     * @return SelectionEtatForm
     */
    public function setSelectionEtatForm(SelectionEtatForm $selectionEtatForm): SelectionEtatForm
    {
        $this->selectionEtatForm = $selectionEtatForm;
        return $this->selectionEtatForm;
    }

}