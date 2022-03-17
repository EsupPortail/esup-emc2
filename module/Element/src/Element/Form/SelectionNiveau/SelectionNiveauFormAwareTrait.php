<?php

namespace Element\Form\SelectionNiveau;

trait SelectionNiveauFormAwareTrait {

    /** @var SelectionNiveauForm */
    private $selectionNiveauForm;

    /**
     * @return SelectionNiveauForm
     */
    public function getSelectionNiveauForm(): SelectionNiveauForm
    {
        return $this->selectionNiveauForm;
    }

    /**
     * @param SelectionNiveauForm $selectionNiveauForm
     * @return SelectionNiveauForm
     */
    public function setSelectionNiveauForm(SelectionNiveauForm $selectionNiveauForm): SelectionNiveauForm
    {
        $this->selectionNiveauForm = $selectionNiveauForm;
        return $this->selectionNiveauForm;
    }

}