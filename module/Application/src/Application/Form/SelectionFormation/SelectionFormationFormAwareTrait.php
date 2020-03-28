<?php

namespace Application\Form\SelectionFormation;

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
    public function setSelectionFormationForm($selectionFormationForm)
    {
        $this->selectionFormationForm = $selectionFormationForm;
        return $this->selectionFormationForm;
    }

}
