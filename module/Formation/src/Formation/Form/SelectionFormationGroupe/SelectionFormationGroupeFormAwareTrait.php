<?php

namespace Formation\Form\SelectionFormationGroupe;

trait SelectionFormationGroupeFormAwareTrait
{

    /** @var SelectionFormationGroupeForm */
    private $selectionFormationGroupeForm;

    /**
     * @return SelectionFormationGroupeForm
     */
    public function getSelectionFormationGroupeForm() : SelectionFormationGroupeForm
    {
        return $this->selectionFormationGroupeForm;
    }

    /**
     * @param SelectionFormationGroupeForm $selectionFormationGroupeForm
     * @return SelectionFormationGroupeForm
     */
    public function setSelectionFormationGroupeForm(SelectionFormationGroupeForm $selectionFormationGroupeForm) : SelectionFormationGroupeForm
    {
        $this->selectionFormationGroupeForm = $selectionFormationGroupeForm;
        return $this->selectionFormationGroupeForm;
    }

}
